var _data = [];
var _i = 0;

function main() {
    var response = UrlFetchApp.fetch('http://google.parse.broavto.com/api/ad-word/get-json?scriptId=1');
    //Logger.log(response);
    if(response == ''){
        return '-';
    }

    _data = JSON.parse(response.getContentText());


    if(_data.campaignId === undefined || _data.data === undefined || !Array.isArray(_data.data)){
        return '-';
    }
    if(_data.groupNamePref === undefined){
        return '-';
    }

    var campaignIterator = AdsApp.campaigns()
        .withCondition('Id = '+_data.campaignId)
        .get();

    if (campaignIterator.hasNext()) {
        var campaign = campaignIterator.next();

        _data.data.forEach(function(v, i) {
            _i = i;

            if(_data.data[_i].headline_part1 !== null && _data.data[_i].headline_part1 != '' && _data.data[_i].description !== null && _data.data[_i].description != '' && _data.data[_i].keywords !== null && _data.data[_i].keywords != '' && Array.isArray(_data.data[_i].keywords)){
                adGroupAdd(campaign);
            }
        });
    }
}




function adGroupAdd(campaign) {
    campaign.newAdGroupBuilder()
        .withName(_data.groupNamePref+'_'+_data.data[_i].id)
        .build();

    var adGroupIterator = AdsApp.adGroups()
        .withCondition('Name = "'+_data.groupNamePref+'_'+_data.data[_i].id+'"')
        .get();

    if (adGroupIterator.hasNext()) {
        var adGroup = adGroupIterator.next();

        var expandedTextAdBuilder = adGroup.newAd().expandedTextAdBuilder();

        expandedTextAdBuilder
            .withHeadlinePart1(_data.data[_i].headline_part1)
            .withHeadlinePart2(_data.data[_i].headline_part2)
            .withDescription(_data.data[_i].description)
            .withFinalUrl(_data.data[_i].final_url);

        if(_data.data[_i].headline_part3 !== null && _data.data[_i].headline_part3 != ''){
            expandedTextAdBuilder
                .withHeadlinePart3(_data.data[_i].headline_part3);
        }

        if(_data.data[_i].description2 !== null && _data.data[_i].description2 != ''){
            expandedTextAdBuilder
                .withDescription2(_data.data[_i].description2);
        }

        if(_data.data[_i].path1 !== null && _data.data[_i].path1 != ''){
            expandedTextAdBuilder
                .withPath1(_data.data[_i].path1);
        }

        if(_data.data[_i].path2 !== null && _data.data[_i].path2 != ''){
            expandedTextAdBuilder
                .withPath2(_data.data[_i].path2);
        }

        expandedTextAdBuilder
            .build();


        _data.data[_i].keywords.forEach(function(v) {
            adGroup.newKeywordBuilder()
                .withText(v)
                .build();
        });
    }
}