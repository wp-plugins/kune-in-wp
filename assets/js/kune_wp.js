/*! Kune in Wordpress - v0.1.0
 * http://kune.ourproject.org/kune-wp/
 * Copyright (c) 2014; * Licensed GPLv3+ */
/*global document*/

var kuneIdToEmbed; // something like 'troco.docs.4590.2612';
var kuneEmbedInit;

var init = function() {
    // General configuration
    document.confEmbed(kuneEmbedConf);
    // We embed in the specified div that #kunegroup.doc
    // TODO add here also the container id
    document.embed(kuneIdToEmbed);
};
/* jshint ignore:end */

function add_kune_doc(token, container, options) {
    // kuneIdToEmbed = options.id;
    kuneIdToEmbed = token;
    
    if (document.confEmbed && document.embed) {
        // kune embed code is ready
        init();
    } else {
        // kune embed code is ready, we configure a hook
        kuneEmbedInit = init;
    }
} 
