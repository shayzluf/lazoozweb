(function($) {
    var ve = function(element, options) {
        this.el = element;
        this.$el = $(this.el);
        this.options = $.extend({
            pattern: '' || {},
            blur: '' || {},
            overlay: '' || {},
            transparent: '' || {},
            slider: {
                target: '' || {},
                result: '' || {}
            },
            colorpicker: {
                text: '' || {},
                background: '' || {},
                overlay: '' || {}
            },
            image: {
                background: '' || {},
                id: 0,
                remove: '' || {},
                set: '' || {}
            }
        }, options);
        this.init();
    };
    ve.prototype = {
        constructor: ve,
        background: $('<div>').addClass('page-background'),
        overlay: $('<div>').addClass('page-overlay'),
        blur: $('<div>').addClass('page-blur'),
        init: function() {
            var self = this;
            var update = function() {
                if (typeof (tinyMCE) !== "undefined") {
                    if (tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden()) {
                        var iframe = $("#content_ifr");
                        iframe.contents().find("body").css({
                            'position': 'relative',
                            'z-index': 2,
                            'background-color': 'transparent',
                            'color': $(self.options.colorpicker.text).val()
                        });
                    }
                }
                if ($(self.options.image.background).val() !== '' && $(self.options.transparent).val() === 0) {
                    self.background.css({
                        'background': 'url(' + $(self.options.image.background).val() + ') rgba(' + self.color(self).join(', ') + ', ' + $(self.options.overlay).val() + ')',
                        'background-size': 'cover'
                    });
                }
            };
            setInterval(update, 500);
            this.container().load().handlers();
        },
        slider: function() {
            var self = this;
            $(this.options.slider.target).slider({
                value: $(self.options.overlay).val(),
                min: 0,
                max: 1,
                step: 0.01,
                slide: function(event, ui) {
                    $('.page-overlay').css({
                        'background-color': 'rgba(' + self.color(self).join(', ') + ', ' + ui.value + ')'
                    });
                    $(self.options.slider.result).text(ui.value);
                    $(self.options.overlay).val(ui.value);
                }
            });
        },
        load: function() {
            this.bg = {}, this.rgba_ovelay = this.convert($(this.options.colorpicker.overlay).val()), this.rgba = this.convert($(this.options.colorpicker.background).val()), blur = $(this.options.blur + ' option:selected').val();

            if ($(this.options.colorpicker.background).val() != $(this.options.colorpicker.background).attr('data-default-color') && $(this.options.transparent).val() == 0) {
                $(this.options.transparent).removeAttr('checked').val(0);
            }

            if ($(this.options.image.background).val() !== '' && $(this.options.transparent).val() == 0) {
                $(this.options.transparent).removeAttr('checked').val(0);
                this.bg = {
                    'background': 'url(' + $(this.options.image.background).val() + ') rgba(' + this.rgba.join(', ') + ', ' + $(this.options.overlay).val() + ')',
                    'background-size': 'cover'
                };
            } else {
                this.bg = {
                    'background': $(this.options.pattern).val() != 0
                            ? 'url(' + $('#template-path').val() + '/' + $(this.options.pattern).val() + ') repeat'
                            : $(this.options.colorpicker.background).val() || $(this.options.colorpicker.background).attr('data-default-color')
                };
                $(this.options.image.remove).hide();
            }
            this.append();
            if ($(this.options.image.background).val() !== '' && $(this.options.transparent).val() == 0 && blur > 0) {
                $('.page-blur').blurjs({
                    source: 'div.page-background',
                    radius: blur
                }).css({
                    'background-size': 'cover'
                });
            } else {
                $(this.options.blur).attr('disabled', true);
            }
            if ($(this.options.pattern).val() != 0 && $(this.options.transparent).val() == 0) {
                $(this.options.transparent).removeAttr('checked').val(0);
            }

            this.$el.css({
                'color': $('.wp-color-picker-text').val()
            });

            $("#content_ifr").contents().find("body").css({
                'color': $(this.options.colorpicker.text).val()
            });

            return this;
        },
        container: function() {
            $('#postbox-container-1 .meta-box-sortables').prepend($('<div>').addClass('ve-area postbox').append($('<ul>').addClass('properties inside')));
            $('.ve-area').prepend('<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Visual Editor</span></h3>');
            $('#post [id*="ve_page_"]').each(function(index) {
                var title = $(this).find('.hndle').html(),
                        html = $(this).find('.hndle').next().html();
                if (typeof title !== 'undefined') {
                    $('.properties').append($('<li>').html(function() {
                        return title + html;
                    }));
                    $('.metabox-prefs label[for="' + $(this).attr('id') + '-hide"]').hide();
                }
            }).remove();
            if (this.options.slider !== '') {
                this.slider();
            }
            if (this.length(this.options.colorpicker)) {
                this.colorpicker();
            }
            return this;
        },
        colorpicker: function() {
            for (var i in this.options.colorpicker) {
                $(this.options.colorpicker[i]).wpColorPicker();
            }
        },
        handlers: function(event) {
            var self = this, selector = $('<a>').addClass('button').attr({id: 'wp-ve', href: '#'}).text('Visual Editor');

            selector.on('click', function(e) {
                e.preventDefault();
                $('.ve-area').toggle();
                $('.page-overlay, .page-background, .page-blur').toggle();
            }).appendTo($(this.$el).parent().prev().find('.wp-media-buttons'));

            $('.iris-picker-inner').bind('mousemove mousedown', function() {
                self.change(self);
            });
            $('.wp-picker-holder, .wp-picker-default').on('click', function() {
                self.change(self);
            });
            $(this.options.transparent).click(function(e) {
                if ($(this).val() == 1) {
                    $(this).removeAttr('checked').val(0);
                } else {
                    $(this).val(1);
                    $("#content_ifr").contents().find("body").css({
                        'background': 'transparent'
                    });
                    $(self.options.image.background).attr('value', '');
                    $('.page-background, .page-blur').removeAttr('style');
                    $(self.options.blur + " option[value='0']").attr('selected', true);
                }
            });
            $(this.options.blur).change(function() {
                if ($(self.options.image.background).val() != '') {
                    $('.page-blur').blurjs({
                        source: 'div.page-background',
                        radius: $(this).val()
                    }).css({
                        'background-size': 'cover'
                    });
                }
            });
            var el = this.options.pattern.split(' ');
            $(el[0]).on('change', function() {
                $(self.options.transparent).removeAttr('checked').val(0);
                var css = {}, id = $(self.options.image.id).val();
                if (id == 0 || id == '') {
                    if ($(this).val() == 0) {
                        css = {
                            'background': 'rgba(' + self.convert($(self.options.colorpicker.background).val()).join(', ') + ', ' + $(self.options.overlay).val() + ')'
                        };

                        if ($(self.options.image.background).val() != '') {
                            $(self.options.blur).removeAttr('disabled');
                        }
                        $('.page-background').css(css);
                    } else {
                        css = {
                            'background': 'transparent'
                        };
                        $(self.options.blur).attr('disabled', true);
                        $('.page-background').css({'background': 'url(' + $('#template-path').val() + '/' + $(this).val() + ') repeat'});
                        $("#content_ifr").contents().find("body").css({'background': 'url(' + $('#template-path').val() + '/' + $(this).val() + ') repeat'});
                    }
                }
                self.$el.css(css);
                $("#content_ifr").contents().find("body").css(css);
            });
            $(this.options.image.remove).click(function(e) {
                e.preventDefault();
                var pattern = $(self.options.pattern).val();
                $(self.options.image.background).attr('value', '');
                $('.page-background, .page-blur').removeAttr('style');
                $(self.options.image.set).text('Set Background Image');
                $(self.options.image.id).val(0);
                $(this).hide();
                $(self.options.blur).attr('disabled', true);
                $('.page-background').css({
                    'background': pattern != 0 ? 'url(' + $('#template-path').val() + '/' + pattern + ') repeat' : 'transparent'
                });
            });
            this.frame();
        },
        change: function(obj) {
            var background = this.convert($(obj.options.colorpicker.background).val());
            var css = {};

            $('.page-overlay').css({
                'background': 'rgba(' + obj.color(obj).join(', ') + ', ' + $(obj.options.overlay).val() + ')'
            });
            if ($(obj.options.pattern).val() == 0 && $(obj.options.image.background).val() == '') {
                if ($(obj.options.transparent).val() == 0) {
                    css = {
                        'background-color': 'rgb(' + background.join(', ') + ')'
                    };
                }
            }
            if ($(obj.options.colorpicker.background).val() != $(obj.options.colorpicker.background).attr('data-default-color')) {
                $(obj.options.transparent).removeAttr('checked').val(0);
            }
            $('.page-background').css(css);
            this.$el.css({
                'color': $(obj.options.colorpicker.text).val()
            });
            $("#content_ifr").contents().find("body").css({
                'color': $(obj.options.colorpicker.text).val()
            });
        },
        frame: function() {
            var frame = wp.media.frames.file_frame = wp.media({
                title: 'Select the image',
                multiple: false,
                button: {
                    text: 'Add selected images'
                },
                library: {
                    type: 'image'
                }
            }), self = this;
            frame.on('select', function() {
                var image = frame.state().get('selection').toArray();
                $.each(image, function(index, value) {
                    self.addImage(value.toJSON().id);
                });
            });
            $(this.options.image.set).click(function(e) {
                e.preventDefault();
                frame.open();
            });
        },
        addImage: function(id) {
            var background = this.getImage(id), thumbnail = $('<img>').attr({'src': background.thumbnail}), css = {
                'background': 'transparent'
            };
            $(this.options.image.set).html(thumbnail);
            $(this.options.image.background).val(background.image);
            $(this.options.image.id).val(id);
            $(this.options.image.remove).show();
            $(this.options.blur).removeAttr('disabled');
            this.$el.css(css);
            $("#content_ifr").contents().find("body").css(css);
            $(this.options.transparent).removeAttr('checked').val(0);
            $('.page-background').css({
                'background': 'url(' + background.image + ')',
                'background-size': 'cover'
            });
            $('.page-blur').removeAttr('style');
            $(this.options.blur + ' option[value="0"]').attr('selected', true);
            $('.page-overlay').removeAttr('style').css({
                'background-color': 'rgba(' + this.color(this).join(', ') + ', ' + $(this.options.overlay).val() + ')' + ')'
            });
        },
        getImage: function(id) {
            var result;
            $.ajax({
                async: false,
                type: "POST",
                dataType: 'json',
                url: ajax.url,
                data: {
                    action: 'get_attachment',
                    id: id
                },
                success: function(data) {
                    result = data;
                }
            });
            return result;
        },
        color: function(obj) {
            return obj.convert($(obj.options.colorpicker.overlay).val() || $(obj.options.colorpicker.overlay).attr('data-default-color'));
        },
        convert: function(hex) {
            if (!hex) {
                hex = '#ffffff';
            }
            var rgba = parseInt(hex.substring(1), 16);
            var r = (rgba & 0xff0000) >> 16;
            var g = (rgba & 0x00ff00) >> 8;
            var b = rgba & 0x0000ff;
            return [r, g, b];
        },
        append: function() {
            this.$el.parent().append(this.background.css(this.bg));
            this.$el.parent().append(this.blur);
            this.$el.parent().append(this.overlay.css({
                'background-color': 'rgba(' + this.rgba_ovelay.join(', ') + ', ' + $(this.options.overlay).val() + ')'
            }));
        },
        length: function(o) {
            var size = 0, key;
            for (key in o)
                if (o.hasOwnProperty(key))
                    size++;
            return size;
        }
    };
    $.fn.vEditor = function(option) {
        return new ve(this, option);
    };
})(window.jQuery);