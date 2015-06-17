/*global document*/
/* http://code.tutsplus.com/tutorials/guide-to-creating-your-own-wordpress-editor-buttons--wp-30182 */

(function() {

    function checkHash(url) {
        // Should return true if url has this format http://kune.cc/#!sandbox.docs.922.758
        var match = url.match(/#[!]{0,1}.*/);
        if (!match) {
            return false;
        }
        return match[0].split(".").length === 4;
    }

    function validateUrl(value) {
        // http://stackoverflow.com/questions/8667070/javascript-regular-expression-to-validate-url
        var regexp = /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
        return regexp.test(value);
    }

    tinymce.create('tinymce.plugins.Kune', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event* of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            var sampleUrl = "http://kune.cc/#!sandbox.docs.922.758";
            ed.addButton('kunebutton', {
                title : 'Insert a kune document',
                image : url+'/../../images/kune-icon20.png',
                onclick : function() {
                    // TODO use ed.windowManager.open in the future instead of prompt
                    // http://www.tinymce.com/wiki.php/api3:class.tinymce.windowmanager
                    var id = prompt("Insert the URL of your kune document:", sampleUrl);
                    if (validateUrl(id) && checkHash(id)) {
                        if (id !== null && id !== 'undefined') {
                            ed.execCommand('mceInsertContent', false, '[kune url="'+id+'"]');
                        }
                    } else {
                        ed.windowManager.alert("Wrong kune document url, you should type something like: " + sampleUrl);
                    }
                }
            });
            ed.addButton( 'kunebutton_dev', {
                //text: 'Insert Shortcode',
                title : 'Insert a kune document (dev)',
                image : url+'/../../images/kune-icon20.png',
                cmd: 'plugin_command'
            });

            // Called when we click the Insert Gistpen button
            ed.addCommand( 'plugin_command', function() {
                // Calls the pop-up modal
                ed.windowManager.open({
                    // Modal settings
                    title: 'Insert Shortcode',
                    width: jQuery( window ).width() * 0.7,
                    // minus head and foot of dialog box
                    height: (jQuery( window ).height() - 36 - 50) * 0.7,
                    inline: 1,
                    id: 'plugin-slug-insert-dialog',
                    buttons: [{
                        text: 'Insert',
                        id: 'plugin-slug-button-insert',
                        "class": 'insert',
                        onclick: function( e ) {
                            insertShortcode();
                        }
                    },
                              {
                                  text: 'Cancel',
                                  id: 'plugin-slug-button-cancel',
                                  onclick: 'close'
                              }]
                });

                appendInsertDialog();

            });
        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : "Kune Shortcode",
                author : 'Vicente J. Ruiz Jurado from Comunes Collective',
                authorurl : 'http://kune.ourproject.org/kune-wp/',
                infourl : 'http://kune.ourproject.org/',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('kunebutton', tinymce.plugins.Kune);
    tinymce.PluginManager.add('kunebutton_dev', tinymce.plugins.Kune);

    function appendInsertDialog () {
        var dialogBody = jQuery( '#plugin-slug-insert-dialog-body' ).append( '[Loading element like span.spinner]' );

        // Get the form template from WordPress
        jQuery.post( ajaxurl, {
            action: 'plugin_slug_insert_dialog'
        }, function( response ) {
            template = response;

            dialogBody.children( '.loading' ).remove();
            dialogBody.append( template );
            jQuery( '.spinner' ).hide();
        });
    }
})();
