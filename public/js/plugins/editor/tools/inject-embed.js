function injectEmbed (url, caption, mode) {
    let config = Embed.pasteConfig;
    editor.inject.Embed(mode || 'inline', {
            source: url,
            caption: caption
        },
        editor,
        config
    )
}
Embed.init=e=>{"inject"in e||(e.inject={}),"Embed"in e.inject||(e.inject.Embed=(e,t,i,n,o)=>{Embed.prepare({config:n});const r=Embed.services,{patterns:s}=(Object.entries(r),Embed.pasteConfig);let c=t.source;o&&(c="boolean"==typeof o?prompt("Try your url",c):prompt(o,c));let d="";const l=Object.keys(r);let m=0;const b=l.length;for(;!d&&m<b;)s[l[m]].test(c)&&(d=l[m]),m+=1;if(c&&d){const o=r[d],{regex:s,embedUrl:l,width:m,height:b,id:a=(e=>e.shift())}=o,p=s.exec(c).slice(1);t={service:d,source:c,embed:l.replace(/<\%\= remote\_id \%\>/g,a(p)),width:m,height:b,caption:t.caption||""};const g=new Embed({data:t,api:i});if("inline"==e)h=g,window.getSelection().getRangeAt(0).insertNode(h.render());else{const o=Number.isFinite(e)?e:i.blocks.getCurrentBlockIndex()+1;i.blocks.insert("embed",t,n,o,!0)}}else c&&alert("No service for this url, check your services config ;\nfor now only "+l.join(",")+" are enabled");var h})};

//Embed.init(editor);
//injectEmbed ('https://www.youtube.com/watch?v=bOPAF6EN1Co')

