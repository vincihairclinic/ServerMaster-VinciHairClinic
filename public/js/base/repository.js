var base_repository = {
    logObjectAsTable: function(response, html) {
        html = (html === undefined) ? '' : html;
        if(typeof response[0] === 'object'){
            $.each(response, function (i, v) {
                html += logObjectAsTable(v);
                html += '<tr><td style="padding-right: 20px; text-align: right;">&nbsp;</td><td>&nbsp;</td></tr>';
                html += '<tr><td style="padding-right: 20px; text-align: right;">&nbsp;</td><td>&nbsp;</td></tr>';
            });
        }else {
            for (var key in response) {
                html += '<tr><td style="padding-right: 20px; text-align: right; vertical-align: top;"><b>' + key + '</b></td><td>' + response[key] + '</td></tr>';
            }
        }
        return html;
    },

    decodeHTMLEntities: function(text) {
        var entities = [
            ['amp', '&'],
            ['apos', '\''],
            ['#x27', '\''],
            ['#x2F', '/'],
            ['#39', '\''],
            ['#47', '/'],
            ['lt', '<'],
            ['gt', '>'],
            ['nbsp', ' '],
            ['quot', '"']
        ];

        for (var i = 0, max = entities.length; i < max; ++i)
            text = text.replace(new RegExp('&'+entities[i][0]+';', 'g'), entities[i][1]);

        return text;
    },

    formatMoney: function(amount, decimalCount, decimal, thousands) {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            var i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            var j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    },

    getBaseUrl: function() {
        return window.location.protocol+'//'+window.location.host;
    },

    getFullUrl: function() {
        return window.location.protocol+'//'+window.location.host+window.location.pathname;
    },

    getQueryVariable: function(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if(pair[0] == variable){return pair[1];}
        }
        return(false);
    },

    isInRoute: function(path) {
        var query = window.location.pathname.substring(1);
        return query.includes(path);
    },

    clearStrToSearch: function(str) {
        str = str.replace(/[,]+/g,'.');
        str = str.replace(/[.]+/g,'.');
        str = str.replace(/[-]+/g,'-');
        str = str.replace(/[+/\\*!@?]/g,' ');

        str = str.replace(/[^[.-]0-9a-zа-яіIїЇЁё]/gim,' ');
        str = str.trim();

        str = str.replace(/\s[.]/g,' ');
        str = str.replace(/\s[-]/g,' ');

        str = str.replace(/[.]\s/g,' ');
        str = str.replace(/[-]\s/g,' ');

        while(['.', '-'].indexOf(str.charAt(0)) !== -1 ){
            str = str.substr(1);
        }

        str = str.trim();
        str = str.replace(/\s+/g,'+');

        return str;
    }
};








