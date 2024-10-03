var jqteClear = {
    classEditableEl: 'jqte_editor',

    init: function (){
        document.querySelectorAll('.'+jqteClear.classEditableEl+'[contenteditable]').forEach(function (el) {
            jqteClear.onPasteEditableDiv(el);
            jqteClear.onBlurEditableDiv(el);
            jqteClear.onKeydown(el);
            jqteClear.onFocusEditableDiv(el);
        });
    },


    onKeydownDoubleEnter: false,
    onKeydown: function(el){
        if(!el.classList.contains("keydown_event")) {
            el.classList.add("keydown_event");
            el.addEventListener("keydown", function (e) {
                if(e.target.classList.contains(jqteClear.classEditableEl)){
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if (code === 13 && !e.ctrlKey && !e.shiftKey && !e.metaKey && !e.altKey) {
                        if(!jqteClear.onKeydownDoubleEnter && $(el).parent().find('.jqte_tool_text').text() !== 'h2'){
                            e.preventDefault();
                            e.stopPropagation();
                            var node = document.createElement('br');
                            var bnode = document.createTextNode('\u200B');
                            var selection = window.getSelection();
                            var range = selection.getRangeAt(0);
                            range.deleteContents();
                            range.collapse(false);
                            range.insertNode(bnode);
                            range.insertNode(node);
                            range.selectNode(node);
                            range.collapse(false);
                            selection.removeAllRanges();
                            selection.addRange(range);
                            jqteClear.onKeydownDoubleEnter = true;
                            return;
                        }else {
                            setTimeout(function (){
                                $(el).parent().find('.jqte_format.jqte_format_0').trigger('click');
                            }, 1)
                        }
                        jqteClear.onKeydownDoubleEnter = false;
                        return;
                    }
                }
                jqteClear.onKeydownDoubleEnter = false;
            });
        }
    },

    onFocusEditableDiv: function (el){
        if(!el.classList.contains("focus_event")) {
            el.classList.add("focus_event");
            if(empty($(el).html())){
                $(el).parent().find('.jqte_tool.jqte_tool_1').trigger('click');
                $(el).parent().find('.jqte_format.jqte_format_1').trigger('click');
            }
            el.addEventListener("focus", function (e) {
                if(e.target.classList.contains(jqteClear.classEditableEl)) {
                    setTimeout(function (){
                        if(empty($(el).html())){
                            $(el).parent().find('.jqte_format.jqte_format_1').trigger('click');
                        }
                    }, 1)
                }
            });
        }
    },

    onBlurEditableDiv: function (el){
        if(!el.classList.contains("blur_event")) {
            el.classList.add("blur_event");
            el.addEventListener("blur", function (e) {
                if(e.target.classList.contains(jqteClear.classEditableEl)) {
                    setTimeout(function () {
                        var text = el.innerHTML;
                        if(text !== undefined){
                            text = jqteClear.clearPasteHtml(text);
                            //text = HtmlSanitizer.SanitizeHtml(text);
                            if(el.innerHTML !== text){
                                el.innerHTML = text;
                            }
                        }
                    }, 1)
                }
            });
        }
    },

    onPasteEditableDiv: function (el){
        if(!el.classList.contains("paste_event")){
            el.classList.add("paste_event");
            el.addEventListener("paste", function(e) {
                var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                //text = jqteClear.baseClearPasteText(text);
                text = text.replace(/\n/g, "<br>");
                text = text.replace(/\s\s+/g, ' ');
                e.preventDefault();
                e.stopPropagation();
                $(el).parent().find('.jqte_format.jqte_format_0').trigger('click');
                document.execCommand("insertHTML", false, text);

                setTimeout(function () {
                    var text = el.innerHTML;
                    if(text !== undefined){
                        text = jqteClear.clearPasteHtml(text);
                        console.log(text)
                        if(el.innerHTML !== text){
                            el.innerHTML = text;
                        }
                    }
                }, 1)
            });
        }
    },

    baseClearPasteText: function (text){
        text = text.replace(/&nbsp;/g, " ");
        text = text.replace(/\s+/g, " ");
        text = text.trim();
        return text;
    },

    clearPasteHtml: function (text, step) {
        text = text.replace(/\r/g, " ");
        text = text.replace(/\t/g, " ");
        text = text.replace(/\s\s+/g, ' ');
        //text = text.replace(/\n/g, "<br>");

        /*text = text.replace(/> /g, '>');
        text = text.replace(/ </g, '<');*/

        text = text.replace(/<img.*?src="(.*?)"[^>]+>/gi, '<figure><img src="$1"></figure>');

        text = text.replace(/  +/g, ' ');
        text = text.replace(/_/g, "3423443242894234");
        text = text.replace(/ /g, "_");
        text = text.replace(/>/g, "\r");
        text = text.replace(/</g, "\t");

        text = text.replace(/\tbr(_\S+)?(\/)?\r/gi, "3454724389789798678");
        text = text.replace(/\n/g, "3454724389789798678");


        ['a', 'bdi', 'bdo', 'acronym', 'abbr', 'address', 'big', 'center', 'del', 'dfn', 'font', 'ins', 'kbd', 'meter', 'progress', 'rp', 'ruby', 's', 'samp', 'small', 'strike', 'sub', 'sup', 'time', 'tt', 'u', 'var', 'wbr', 'dir', 'dl', 'dt', 'dd', 'cite', 'thead', 'tbody', 'tfoot', 'body', 'html'].forEach(function (v, i) {
            text = text.replace((new RegExp("\\t("+v+")(_\\S+)?\\r", 'gi')), '');
            text = text.replace((new RegExp("\\t\/("+v+")\\r", 'gi')), '');
        });

        ['form', 'input', 'textarea', 'button', 'select', 'optgroup', 'option', 'label', 'fieldset', 'legend', 'datalist', 'output', 'frame', 'frameset', 'noframes', 'iframe'/*, 'img'*/, 'map', 'area', 'canvas', 'figcaption', 'svg', 'audio', 'source', 'track', 'video', 'link', 'nav', 'style', 'head', 'aside', 'details', 'dialog', 'summary', 'data', 'meta', 'base', 'basefont', 'script', 'noscript', 'applet', 'embed', 'object', 'param', 'caption', 'template', 'col', 'colgroup', 'source '].forEach(function (v, i) {
            text = text.replace((new RegExp("\\t"+v+"(_\\S+)?\\r(\\S+)?\\t/"+v+"\\r", 'gi')), '');
            text = text.replace((new RegExp("\\t("+v+")(_\\S+)?\\r", 'gi')), '');
            text = text.replace((new RegExp("\\t\/("+v+")\\r", 'gi')), '');
        });

        ['hr', 'li', 'ul', 'ol', 'span', 'p', 'b', 'i', 'h2', 'h3', 'h4', 'code', 'table', 'th', 'tr', 'td', 'mark', 'picture', 'figure'].forEach(function (v, i) {
            text = text.replace((new RegExp("\\t("+v+")(_\\S+)?\\r", 'gi')), "\t$1\r");
        });

        ['strong', 'em'].forEach(function (v, i) {
            text = text.replace((new RegExp("\\t("+v+")(_\\S+)?\\r", 'gi')), "\tb\r");
            text = text.replace((new RegExp("\\t\/("+v+")\\r", 'gi')), "\t/b\r");
        });

        ['blockquote', 'q', 'rt'].forEach(function (v, i) {
            text = text.replace((new RegExp("\\t("+v+")(_\\S+)?\\r", 'gi')), "\ti\r");
            text = text.replace((new RegExp("\\t\/("+v+")\\r", 'gi')), "\t/i\r");
        });

        ['div', 'pre', 'header', 'footer', 'main', 'section', 'article'].forEach(function (v, i) {
            text = text.replace((new RegExp("\\t("+v+")(_\\S+)?\\r", 'gi')), "\tp\r");
            text = text.replace((new RegExp("\\t\/("+v+")\\r", 'gi')), "\t/p\r");
        });

        //text = text.replace(/\tbr(_\S+)?\r/gi, '\tbr\r');

        text = text.replace(/\th1(_\S+)?\r/gi, '\th2\r');
        text = text.replace(/\t\/h1\r/gi, '\t/h2\r');
        ['h5', 'h6'].forEach(function (v, i) {
            text = text.replace((new RegExp("\\t("+v+")(_\\S+)?\\r", 'gi')), "\th4\r");
            text = text.replace((new RegExp("\\t\/("+v+")\\r", 'gi')), "\t/h4\r");
        });


        text = text.replace(/\t\t+/g, "\t");
        text = text.replace(/\r\r+/g, "\r");
        text = text.replace(/\r/g, ">");
        text = text.replace(/\t/g, "<");
        text = text.replace(/_/g, " ");
        text = text.replace(/3423443242894234/g, "_");
        text = text.replace(/3454724389789798678/g, "<br>");
        text = text.replace(/\n/g, "<br>");


        ['li', 'ul', 'ol', 'span', 'p', 'b', 'i', 'h2', 'h3', 'h4', 'code', 'table', 'th', 'tr', 'td', 'mark'].forEach(function (v, i) {
            text = text.replace((new RegExp("<"+v+">(\\s+)?</"+v+">", 'gi')), '');
        });

        text = text.replace(/\s\s+/g, ' ');
        text = text.replace(/\s+/g, ' ');
        //text = HtmlSanitizer.SanitizeHtml(text);
        text = text.replace(/<br(\s)?(\/)?>((\s+)?<br(\s)?(\/)?>)+/g, "<br>");


        text = text.replace(/\u200B/g, "");
        text = text.replace(/&nbsp;/g, " ");

        text = text.replace(/<br(\s)?(\/)?>((\s+)?<br(\s)?(\/)?>)+/g, "<br>");
        text = text.replace(/<br(\s)?(\/)?>/g, "\n");
        text = text.trim();
        text = text.replace(/\n/g, "<br>");

        text = text.replace(/<p>(<br>)+/g, "<p>");
        text = text.replace(/(<br>)+<\/p>/g, "</p>");
        text = text.replace(/(<p><\/p>)+/g, "");

        text = text.replace(/<h2>(<br>)+/g, "<h2>");
        text = text.replace(/(<br>)+<\/h2>/g, "</h2>");
        text = text.replace(/(<h2><\/h2>)+/g, "");

        text = text.replace(/<h3>(<br>)+/g, "<h3>");
        text = text.replace(/(<br>)+<\/h3>/g, "</h3>");
        text = text.replace(/(<h3><\/h3>)+/g, "");

        text = text.replace(/<h4>(<br>)+/g, "<h4>");
        text = text.replace(/(<br>)+<\/h4>/g, "</h4>");
        text = text.replace(/(<h4><\/h4>)+/g, "");

        text = text.replace(/\s+/g, " ");
        text = text.trim();



        text = text.replace(/<span>/g, "");
        text = text.replace(/<\/span>/g, "");

        text = text.replace(/(<h[1-6]>)(<p>.*<\/p>)/g, "$2$1");
        text = text.replace(/(<p>)(<h[1-6]>.*<\/h[1-6]>)/g, "$2$1");

        //-----------------------------------------------------------------

        text = text.replace(/<h[1-6]><p>/g, "<p>");
        text = text.replace(/<\/p><\/h[1-6]>/g, '<\p>');

        text = text.replace(/<p><h[1-6]>/g, "<p>");
        text = text.replace(/<\/h[1-6]><\/p>/g, '<\p>');

        text = text.replace(/<br><p>/g, "<p>");
        text = text.replace(/<\/p><br>/g, '<\p>');
        text = text.replace(/<p><br>/g, "<p>");
        text = text.replace(/<br><\/p>/g, '<\p>');

        text = text.replace(/(<h[1-6]>)<br>/g, "$1");
        text = text.replace(/<br>(<\/h[1-6]>)/g, '$1');
        text = text.replace(/<br>(<h[1-6]>)/g, "$1");
        text = text.replace(/(<\/h[1-6]>)<br>/g, '$1');

        text = text.replace(/<p>(\s+)?<\/p>/g, '');
        text = text.replace(/<h[1-6]>(\s+)?<\/h[1-6]>/g, '');

        text = text.replace(/<p>(\s+)?<br>(\s+)?<\/p>/g, '');
        text = text.replace(/<h[1-6]>(\s+)?<br>(\s+)?<\/h[1-6]>/g, '');

        text = text.replace(/<\/h[1-6]><h[1-6]>/g, ' ');
        text = text.replace(/<\/p><p>/g, '<br>');

        text = text.replace(/\s+/g, " ");
        text = text.trim();

        //-----------------------------------------------------------------

        text = text.replace(/<br>(\s+)?<\/p>/g, '</p>');
        text = text.replace(/<br>(\s+)?<\/h2>/g, '</h2>');
        // TODO need add all h

        text = text.replace(/<p>(\s+)?<br>/g, '<p>');
        text = text.replace(/<h2>(\s+)?<br>/g, '<h2>');
        // TODO need add all h

        text = text.replace(/<p>(\s+)?<hr>(\s+)?<\/p>/g, "<hr>");
        text = text.replace(/<hr>/g, "</p><hr><p>");
        text = text.replace(/<\/p>(\s+)?<\/p>(\s+)?<hr>(\s+)?<p>(\s+)?<p>/g, "</p><hr><p>");
        text = text.replace(/<p>(\s+)?<\/p>/g, '');

        text = text.replace(/<br>(\s+)?<\/p>/g, '</p>');
        text = text.replace(/<br>(\s+)?<\/h2>/g, '</h2>');
        // TODO need add all h

        text = text.replace(/<p>(\s+)?<br>/g, '<p>');
        text = text.replace(/<h2>(\s+)?<br>/g, '<h2>');
        // TODO need add all h

        text = text.replace(/<br>/g, "3454724389789798678");
        text = text.replace(/<hr>/g, "234254789563789033");
        text = text.replace(/<b>/g, "1239872347982734");
        text = text.replace(/<\/b>/g, "4569823402911234");
        text = text.replace(/<i>/g, "5987213401433245");
        text = text.replace(/<\/i>/g, "6238478913240897");

        text = text.replace(/\s/g, ' ');
        text = text.replace(/_/g, "3423443242894234");
        text = text.replace(/ /g, "_");
        text = text.replace(/>/g, "\r");
        text = text.replace(/</g, "\t");


        text = text.replace(/(\t\/\S+\r)(\S+)(\t\S+\r)/g, "$1\tp\r$2\t/p\r$3");
        text = text.replace(/^(\S+)(\t\S+\r)/g, "\tp\r$1\t/p\r$2");
        text = text.replace(/(\t\/\S+\r)(\S+)$/g, "$1\tp\r$2\t/p\r");

        text = text.replace(/\t(h[1-6]\r)(\S+)(\tp\r)/g, "\t$1$2\t/$1$3");
        text = text.replace(/\t(p\r)(\S+)(\th[1-6]\r)/g, "\t$1$2\t/$1$3");

        /*text = text.replace(/<(h[1-6]>)(\S+)(<p>)$/g, "<$1$2</$1$3");
        text = text.replace(/<(p>)(\S+)(<h[1-6]>)$/g, "<$1$2</$1$3");*/

        text = text.replace(/\t\t+/g, "\t");
        text = text.replace(/\r\r+/g, "\r");
        text = text.replace(/\r/g, ">");
        text = text.replace(/\t/g, "<");
        text = text.replace(/_/g, " ");
        text = text.replace(/3423443242894234/g, "_");

        text = text.replace(/3454724389789798678/g, "<br>");
        text = text.replace(/234254789563789033/g, "<hr>");
        text = text.replace(/1239872347982734/g, "<b>");
        text = text.replace(/4569823402911234/g, "</b>");
        text = text.replace(/5987213401433245/g, "<i>");
        text = text.replace(/6238478913240897/g, "</i>");

        text = text.replace(/<p>(\s+)?<hr>(\s+)?<\/p>/g, "<hr>");
        text = HtmlSanitizer.SanitizeHtml(text);

        text = text.replace(/^(\s+)?<hr>(\s+)?/g, "");
        text = text.replace(/^(\s+)?<br>(\s+)?/g, "");
        text = text.replace(/(\s+)?<hr>(\s+)?$/g, "");
        text = text.replace(/(\s+)?<br>(\s+)?$/g, "");
        text = text.replace(/(\s+)?<p>(\s+)?<\/p>(\s+)?/g, '');
        text = text.replace(/(\s+)?<h[1-6]>(\s+)?<\/h[1-6]>(\s+)?/g, '');
        text = text.replace(/^(\s+)?<hr>(\s+)?/g, "");
        text = text.replace(/^(\s+)?<br>(\s+)?/g, "");
        text = text.replace(/(\s+)?<hr>(\s+)?$/g, "");
        text = text.replace(/(\s+)?<br>(\s+)?$/g, "");
        //-----------------------------------------------------------------


        text = text.replace(/\s+/g, " ");
        text = text.trim();

        //console.log(111, text);

        if(step === undefined || step < 3){
            text = jqteClear.clearPasteHtml(text, (step === undefined ? 1 : step+1));
        }

        return text;
    },

    //-------------------------------------------------------------------------------------

    getCursorPosition: function (parent) {
        var selection = document.getSelection();
        var range = new Range;
        range.setStart(parent, 0);
        range.setEnd(selection.anchorNode, selection.anchorOffset);
        return range.toString().length;
    },

    setCursorPosition: function (parent, position) {
        var child = parent.firstChild;
        if(child){
            while(position > 0) {
                var length = child.textContent ? child.textContent.length : 0;
                if(position > length) {
                    position -= length;
                    child = child.nextSibling;
                }
                else {
                    if(child.nodeType == 3) {
                        return document.getSelection().collapse(child, position);
                    }
                    child = child.firstChild;
                }
            }
        }
    },

    setEndOfContenteditable: function (contentEditableElement){
        var range,selection;
        if(document.createRange){
            range = document.createRange();
            range.selectNodeContents(contentEditableElement);
            range.collapse(false);
            selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        }else if(document.selection){
            range = document.body.createTextRange();
            range.moveToElementText(contentEditableElement);
            range.collapse(false);
            range.select();
        }
    },
}