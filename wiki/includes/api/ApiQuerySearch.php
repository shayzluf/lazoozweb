<?php
/**
 *
 *
 * Created on July 30, 2007
 *
 * Copyright © 2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Query module to perform full text search within wiki titles and content
 *
 * @ingroup API
 */
class ApiQuerySearch extends ApiQueryGeneratorBase {

	/**
	 * When $wgSearchType is null, $wgSearchAlternatives[0] is null. Null isn't
	 * a valid option for an array for PARAM_TYPE, so we'll use a fake name
	 * that can't possibly be a class name and describes what the null behavior
	 * does
	 */
	const BACKEND_NULL_PARAM = 'database-backed';

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'sr' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		global $wgContLang;
		$params = $this->extractRequestParams();

		// Extract parameters
		$limit = $params['limit'];
		$query = $params['search'];
		$what = $params['what'];
		$interwiki = $params['interwiki'];
		$searchInfo = array_flip( $params['info'] );
		$prop = array_flip( $params['prop'] );

		// Create search engine instance and set options
		$search = isset( $params['backend'] ) && $params['backend'] != self::BACKEND_NULL_PARAM ?
			SearchEngine::create( $params['backend'] ) : SearchEngine::create();
		$search->setLimitOffset( $limit + 1, $params['offset'] );
		$search->setNamespaces( $params['namespace'] );

		$query = $search->transformSearchTerm( $query );
		$query = $search->replacePrefixes( $query );

		// Perform the actual search
		if ( $what == 'text' ) {
			$matches = $search->searchText( $query );
		} elseif ( $what == 'title' ) {
			$matches = $search->searchTitle( $query );
		} elseif ( $what == 'nearmatch' ) {
			$matches = SearchEngine::getNearMatchResultSet( $query );
		} else {
			// We default to title searches; this is a terrible legacy
			// of the way we initially set up the MySQL fulltext-based
			// search engine with separate title and text fields.
			// In the future, the default should be for a combined index.
			$what = 'title';
			$matches = $search->searchTitle( $query );

			// Not all search engines support a separate title search,
			// for instance the Lucene-based engine we use on Wikipedia.
			// In this case, fall back to full-text search (which will
			// include titles in it!)
			if ( is_null( $matches ) ) {
				$what = 'text';
				$matches = $search->searchText( $query );
			}
		}
		if ( is_null( $matches ) ) {
			$this->dieUsage( "{$what} search is disabled", "search-{$what}-disabled" );
		} elseif ( $matches instanceof Status && !$matches->isGood() ) {
			$this->dieUsage( $matches->getWikiText(), 'search-error' );
		}

		$apiResult = $this->getResult();
		// Add search meta data to result
		if ( isset( $searchInfo['totalhits'] ) ) {
			$totalhits = $matches->getTotalHits();
			if ( $totalhits !== null ) {
				$apiResult->addValue( array( 'query', 'searchinfo' ),
					'totalhits', $totalhits );
			}
		}
		if ( isset( $searchInfo['suggestion'] ) && $matches->hasSuggestion() ) {
			$apiResult->addValue( array( 'query', 'searchinfo' ),
				'suggestion', $matches->getSuggestionQuery() );
		}

		// Add the search results to the result
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );
		$titles = array();
		$count = 0;
		$result = $matches->next();

		while ( $result ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional items to be had. Stop here...
				$this->setContinueEnumParameter( 'offset', $params['offset'] + $params['limit'] );
				break;
			}

			// Silently skip broken and missing titles
			if ( $result->isBrokenTitle() || $result->isMissingRevision() ) {
				$result = $matches->next();
				continue;
			}

			$title = $result->getTitle();
			if ( is_null( $resultPageSet ) ) {
				$vals = array();
				ApiQueryBase::addTitleInfo( $vals, $title );

				if ( isset( $prop['snippet'] ) ) {
					$vals['snippet'] = $result->getTextSnippet( $terms );
				}
				if ( isset( $prop['size'] ) ) {
					$vals['size'] = $result->getByteSize();
				}
				if ( isset( $prop['wordcount'] ) ) {
					$vals['wordcount'] = $result->getWordCount();
				}
				if ( isset( $prop['timestamp'] ) ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $result->getTimestamp() );
				}
				if ( !is_null( $result->getScore() ) && isset( $prop['score'] ) ) {
					$vals['score'] = $result->getScore();
				}
				if ( isset( $prop['titlesnippet'] ) ) {
					$vals['titlesnippet'] = $result->getTitleSnippet( $terms );
				}
				if ( !is_null( $result->getRedirectTitle() ) ) {
					if ( isset( $prop['redirecttitle'] ) ) {
						$vals['redirecttitle'] = $result->getRedirectTitle();
					}
					if ( isset( $prop['redirectsnippet'] ) ) {
						$vals['redirectsnippet'] = $result->getRedirectSnippet( $terms );
					}
				}
				if ( !is_null( $result->getSectionTitle() ) ) {
					if ( isset( $prop['sectiontitle'] ) ) {
						$vals['sectiontitle'] = $result->getSectionTitle()->getFragment();
					}
					if ( isset( $prop['sectionsnippet'] ) ) {
						$vals['sectionsnippet'] = $result->getSectionSnippet();
					}
				}
				if ( isset( $prop['hasrelated'] ) && $result->hasRelated() ) {
					$vals['hasrelated'] = '';
				}

				// Add item to results and see whether it fits
				$fit = $apiResult->addValue( array( 'query', $this->getModuleName() ),
					null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'offset', $params['offset'] + $count - 1 );
					break;
				}
			} else {
				$titles[] = $title;
			}

			$result = $matches->next();
		}

		$hasInterwikiResults = false;
		if ( $interwiki && $resultPageSet === null && $matches->hasInterwikiResults() ) {
			$matches = $matches->getInterwikiResults();
			$iwprefixes = array();
			$hasInterwikiResults = true;

			// Include number of results if requested
			if ( isset( $searchInfo['totalhits'] ) ) {
				$totalhits = $matches->getTotalHits();
				if ( $totalhits !== null ) {
					$apiResult->addValue( array( 'query', 'interwikisearchinfo' ),
						'totalhits', $totalhits );
				}
			}

			$result = $matches->next();
			while ( $result ) {
				$title = $result->getTitle();
				$vals = array(
					'namespace' => $result->getInterwikiNamespaceText(),
					'title' => $title->getText(),
					'url' => $title->getFullUrl(),
				);

				// Add item to results and see whether it fits
				$fit = $apiResult->addValue( array( 'query', 'interwiki' . $this->getModuleName(), $result->getInterwikiPrefix()  ),
					null, $vals );
				if ( !$fit ) {
					// We hit the limit. We can't really provide any meaningful
					// pagination info so just bail out
					break;
				}

				$result = $matches->next();
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$apiResult->setIndexedTagName_internal( array(
				'query', $this->getModuleName()
			), 'p' );
			if ( $hasInterwikiResults ) {
				$apiResult->setIndexedTagName_internal( array(
					'query', 'interwiki' . $this->getModuleName()
				), 'p' );
			}
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		global $wgSearchType;

		$params = array(
			'search' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'namespace' => array(
				ApiBase::PARAM_DFLT => NS_MAIN,
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_ISMULTI => true,
			),
			'what' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_TYPE => array(
					'title',
					'text',
					'nearmatch',
				)
			),
			'info' => array(
				ApiBase::PARAM_DFLT => 'totalhits|suggestion',
				ApiBase::PARAM_TYPE => array(
					'totalhits',
					'suggestion',
				),
				ApiBase::PARAM_ISMULTI => true,
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'size|wordcount|timestamp|snippet',
				ApiBase::PARAM_TYPE => array(
					'size',
					'wordcount',
					'timestamp',
					'score',
					'snippet',
					'titlesnippet',
					'redirecttitle',
					'redirectsnippet',
					'sectiontitle',
					'sectionsnippet',
					'hasrelated',
				),
				ApiBase::PARAM_ISMULTI => true,
			),
			'offset' => 0,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_SML1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_SML2
			),
			'interwiki' => false,
		);

		$alternatives = SearchEngine::getSearchTypes();
		if ( count( $alternatives ) > 1 ) {
			if ( $alternatives[0] === null ) {
				$alternatives[0] = self::BACKEND_NULL_PARAM;
			}
			$params['backend'] = array(
				ApiBase::PARAM_DFLT => $wgSearchType,
				ApiBase::PARAM_TYPE => $alternatives,
			);
		}

		return $params;
	}

	public function getParamDescription() {
		$descriptions = array(
			'search' => 'Search for all page titles (or content) that has this value',
			'namespace' => 'The namespace(s) to enumerate',
			'what' => 'Search inside the text or titles',
			'info' => 'What metadata to return',
			'prop' => array(
				'What properties to return',
				' size             - Adds the size of the page in bytes',
				' wordcount        - Adds the word count of the page',
				' timestamp        - Adds the timestamp of when the page was last edited',
				' score            - Adds the score (if any) from the search engine',
				' snippet          - Adds a parsed snippet of the page',
				' titlesnippet     - Adds a parsed snippet of the page title',
				' redirectsnippet  - Adds a parsed snippet of the redirect title',
				' redirecttitle    - Adds the title of the matching redirect',
				' sectionsnippet   - Adds a parsed snippet of the matching section title',
				' sectiontitle     - Adds the title of the matching section',
				' hasrelated       - Indicates whether a related search is available',
			),
			'offset' => 'Use this value to continue paging (return by query)',
			'limit' => 'How many total pages to return',
			'interwiki' => 'Include interwiki results in the search, if available'
		);

		if ( count( SearchEngine::getSearchTypes() ) > 1 ) {
			$descriptions['backend'] = 'Which search backend to use, if not the default';
		}

		return $descriptions;
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'ns' => 'namespace',
				'title' => 'string'
			),
			'snippet' => array(
				'snippet' => 'string'
			),
			'size' => array(
				'size' => 'integer'
			),
			'wordcount' => array(
				'wordcount' => 'integer'
			),
			'timestamp' => array(
				'timestamp' => 'timestamp'
			),
			'score' => array(
				'score' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'titlesnippet' => array(
				'titlesnippet' => 'string'
			),
			'redirecttitle' => array(
				'redirecttitle' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'redirectsnippet' => array(
				'redirectsnippet' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'sectiontitle' => array(
				'sectiontitle' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'sectionsnippet' => array(
				'sectionsnippet' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'hasrelated' => array(
				'hasrelated' => 'boolean'
			)
		);
	}

	public function getDescription() {
		return 'Perform a full text search.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'search-text-disabled', 'info' => 'text search is disabled' ),
			array( 'code' => 'search-title-disabled', 'info' => 'title search is disabled' ),
			array( 'code' => 'search-error', 'info' => 'search error has occurred' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=search&srsearch=meaning',
			'api.php?action=query&list=search&srwhat=text&srsearch=meaning',
			'api.php?action=query&generator=search&gsrsearch=meaning&prop=info',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Search';
	}
}
