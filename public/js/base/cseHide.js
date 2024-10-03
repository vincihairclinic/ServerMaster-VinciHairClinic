var cse = {
    url: window.location.hostname,
    urlApi: window.location.hostname,
    r: null,
    attempt: 0,
    init: function(response){
        /*if(!cse.urlApi.includes('img.')){
            cse.urlApi = 'img.'+cse.urlApi;
        }*/

        if(document.getElementById("cse-iframe-fe23hr45y4g") !== null){
            document.getElementById("cse-iframe-fe23hr45y4g").remove();
        }
        if(!cse.empty(response)){
            var q = response.q;
            q = q.replace(/\([^)]+\)/u, ' ');
            q = q.replace(/\s\s+/, ' ');
            response.q = q;

            var iframe = document.createElement("iframe");
            iframe.src = 'https://'+cse.url+'/cse_'+response.type+'.php'+'?q='+response.q;
            iframe.id = "cse-iframe-fe23hr45y4g";
            iframe.style = "display:none;";
            iframe.onload = function () {
                setTimeout(function () {
                    cse.getContent(response, iframe);
                }, 100);
            };

            document.body.appendChild(iframe);
        }else {
            setTimeout(function () {
                cse.get('https://'+cse.urlApi+'/api/cse', function (response) {
                    if(response === ''){
                        return false;
                    }
                    try{
                        response = JSON.parse(response);
                        if(response.q === undefined || response.q === null || response.q === ''){
                            return false;
                        }
                    }catch (e) {
                        return false;
                    }
                    var iframe = document.createElement("iframe");
                    iframe.src = 'https://'+cse.url+'/cse_'+response.type+'.php'+'?q='+response.q;
                    iframe.id = "cse-iframe-fe23hr45y4g";
                    iframe.style = "display:none;";
                    iframe.onload = function () {
                        setTimeout(function () {
                            cse.getContent(response, iframe);
                        }, 100);
                    };

                    document.body.appendChild(iframe);
                });
            }, 1);
        }
    },

    getContent: function(response, iframe, recall){
        if(recall === undefined){
            recall = 0;
        }else if(recall > 4){
            if(document.getElementById("cse-iframe-fe23hr45y4g") !== null){
                document.getElementById("cse-iframe-fe23hr45y4g").remove();
            }
            cse.init();
            return;
        }

        setTimeout(function () {
            var res = [];

            if(iframe === null || iframe.contentWindow === null || iframe.contentWindow.document === null || iframe.contentWindow.document.body === null){
                return cse.getContent(response, iframe, recall + 1)
            }

            if(response.type === 'img'){
                iframe.contentWindow.document.body.querySelectorAll('.gs-image-scalable img').forEach(function(element) {
                    if(element.src === ''){
                        return cse.getContent(response, iframe, recall + 1)
                    }
                    res.push(element.src+'||'+element.width+'||'+element.height)
                });
            }else {
                var titles = [];
                var snippets = [];
                var existImages = [];

                iframe.contentWindow.document.body.querySelectorAll('.gsc-webResult.gsc-result').forEach(function(element) {
                    if(element.querySelector('.gs-visibleUrl-short') !== null && !element.querySelector('.gs-visibleUrl-short').textContent.includes("wikipedia")){
                        if(element.querySelector('.gs-title') === null || element.querySelector('.gs-title').textContent === '' || element.querySelector('.gs-bidi-start-align.gs-snippet') === null || element.querySelector('.gs-bidi-start-align.gs-snippet').textContent === ''){
                            return cse.getContent(response, iframe, recall + 1)
                        }

                        var image = '';
                        element.querySelectorAll('.gs-image img').forEach(function(element) {
                            if(element.src !== '' && !existImages.includes(element.src)){
                                image = '<img src="'+element.src+'" width="'+element.width+'"  height="'+element.height+'"> || ';
                                existImages.push(element.src);
                            }
                        });

                        titles.push(image + cse.clearText(element.querySelector('.gs-title').textContent));
                        snippets.push(cse.clearText(element.querySelector('.gs-bidi-start-align.gs-snippet').textContent));
                    }
                });

                titles = cse.shuffle(titles);
                snippets = cse.shuffle(snippets);

                snippets.forEach(function(element, i) {
                    var text = titles[i] !== '' ? titles[i] : '';
                    text += text !== '' && snippets[i] !== '' ? ' || ' : '';
                    text += snippets[i] !== '' ? snippets[i] : '';
                    res.push(text);
                });
            }

            if(res[0] !== undefined){
                response.response = res;
                cse.post('https://'+cse.urlApi+'/api/cse', response);
            }else if(cse.attempt < 3){
                if(!cse.empty(response) && !cse.empty(response.q)){
                    cse.attempt++;
                    var q = response.q.split(' ').splice(1).join(' ');
                    if(q !== ''){
                        response.q = q;
                        cse.r = response.q;
                        cse.init(response);
                    }else {
                        response.response = res;
                        cse.post('https://'+cse.urlApi+'/api/cse', response);
                    }
                }else {
                    response.response = res;
                    cse.post('https://'+cse.urlApi+'/api/cse', response);
                }
            }else {
                response.response = res;
                cse.post('https://'+cse.urlApi+'/api/cse', response);
            }
        }, 100);
    },

    shuffle: function(a) {
        var j, x, i;
        for (i = a.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = a[i];
            a[i] = a[j];
            a[j] = x;
        }
        return a;
    },

    empty: function(val) {
        if(val === undefined || val === null || !val || val === '' || val === false){
            return true;
        }
        if(val instanceof Array){
            if(val.length <= 0){
                return true;
            }
        }
        return false;
    },

    clearText: function(txt){
        txt = txt.replace(/(?:https?|ftp):\/\/[\n\S]+/g, ' ');
        txt = txt.split('...').join(' ');
        txt = txt.replace(/\s\s+/g, ' ');
        return txt;
    },

    get: function (url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);

        xhr.onreadystatechange = function() {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                if (callback && typeof (callback) === 'function') {
                    callback(xhr.responseText);
                }
            }
        };

        xhr.send();
    },

    post: function (url, params, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        if (callback && typeof (callback) === 'function') {
            xhr.onreadystatechange = function() {
                if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    callback(xhr.responseText);
                }
            };
        }
        xhr.send(JSON.stringify(params));
    }
};

if(!/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)){
    cse.init();
}








