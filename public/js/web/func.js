function autoheight(el) {
    el.style.height = "5px";
    el.style.height = (el.scrollHeight)+"px";
}

function empty(val) {
    if(val instanceof String){
        val = val.trim();
    }
    if(val === undefined || val === null || !val || val === '' || val === 0 || val === false){
        return true;
    }
    if(val instanceof Array){
        if(val.length <= 0){
            return true;
        }
    }
    return false;
}


function replaceAll(val, searchValue, replaceValue) {
    if(val instanceof String){
        return val.split(searchValue).join(replaceValue)
    }
    return val;
}




