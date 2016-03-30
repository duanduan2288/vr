var region = {
    // 获取所有地区
    getAllRegions : function() {
        var regions =  [ {
            "regionID" : 0,
            "title" : "中国",
            "code" : "CN",
            "prefix" : "86"
        }, {
            "regionID" : 1,
            "title" : "中国香港",
            "code" : "HK",
            "prefix" : "852"
        }, {
            "regionID" : 2,
            "title" : "中国澳门",
            "code" : "OM",
            "prefix" : "853"
        }, {
            "regionID" : 3,
            "title" : "中国台湾",
            "code" : "TW",
            "prefix" : "886"
        }, {
            "regionID" : 4,
            "title" : "阿富汗",
            "code" : "AF",
            "prefix" : ""
        }, {
            "regionID" : 4,
            "title" : "阿尔巴尼亚",
            "code" : "AL",
            "prefix" : ""
        }, {
            "regionID" : 4,
            "title" : "阿尔及利亚",
            "code" : "DZ",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "安道尔",
            "code" : "AD",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "安哥拉",
            "code" : "AO",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "安圭拉",
            "code" : "AI",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "阿根廷",
            "code" : "AR",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "亚美尼亚",
            "code" : "AM",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "阿鲁巴",
            "code" : "AW",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "澳大利亚",
            "code" : "AU",
            "prefix" : ""
        },
            {
            "regionID" : 4,
            "title" : "奥地利",
            "code" : "AT",
            "prefix" : ""
        }, {
            "regionID" : 4,
            "title" : "阿塞拜疆",
            "code" : "AZ",
            "prefix" : ""
        }, {
            "regionID" : 4,
            "title" : "巴林",
            "code" : "BH",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "孟加拉国",
            "code" : "BD",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "巴巴多斯",
            "code" : "BB",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "白俄罗斯",
            "code" : "BY",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "百慕大",
            "code" : "BM",
            "prefix" : ""
        },{
            "regionID" : 4,
            "title" : "不丹",
            "code" : "BT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "玻利维亚",
            "code" : "BO",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "荷兰加勒比区",
            "code" : "BQ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "波黑",
            "code" : "BA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "博茨瓦纳",
            "code" : "BW",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "巴西",
            "code" : "BR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "文莱",
            "code" : "BN",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "保加利亚",
            "code" : "BG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "布隆迪",
            "code" : "BI",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "柬埔寨",
            "code" : "KH",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "加拿大",
            "code" : "CA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "佛得角",
            "code" : "CV",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "开曼群岛",
            "code" : "KY",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "智利",
            "code" : "CL",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "哥伦比亚",
            "code" : "CO",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "刚果（金）",
            "code" : "CD",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "刚果（布）",
            "code" : "CG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "哥斯达黎加",
            "code" : "CR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "克罗地亚",
            "code" : "HR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "古巴",
            "code" : "CU",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "塞浦路斯",
            "code" : "CY",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "捷克",
            "code" : "CZ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "丹麦",
            "code" : "DK",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "多米尼克",
            "code" : "DM",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "多米尼加",
            "code" : "DO",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "厄瓜多尔",
            "code" : "EC",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "埃及",
            "code" : "EG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "萨尔瓦多",
            "code" : "SV",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "爱沙尼亚",
            "code" : "EE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "埃塞俄比亚",
            "code" : "ET",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "斐济",
            "code" : "FJ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "芬兰",
            "code" : "FI",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "法国",
            "code" : "FR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "格鲁吉亚",
            "code" : "GE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "德国",
            "code" : "DE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "加纳",
            "code" : "GH",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "希腊",
            "code" : "GR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "格林纳达",
            "code" : "GD",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "危地马拉",
            "code" : "GT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "几内亚",
            "code" : "GN",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "圭亚那",
            "code" : "GY",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "海地",
            "code" : "HT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "洪都拉斯",
            "code" : "HN",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "匈牙利",
            "code" : "HU",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "冰岛",
            "code" : "IS",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "印度",
            "code" : "IN",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "印度尼西亚",
            "code" : "ID",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "伊朗",
            "code" : "IR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "伊拉克",
            "code" : "IQ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "爱尔兰",
            "code" : "IE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "以色列",
            "code" : "IL",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "意大利",
            "code" : "IT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "牙买加",
            "code" : "JM",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "日本",
            "code" : "JP",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "约旦",
            "code" : "JO",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "哈萨克斯坦",
            "code" : "KZ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "肯尼亚",
            "code" : "KE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "韩国",
            "code" : "KR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "科威特",
            "code" : "KW",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "吉尔吉斯斯坦",
            "code" : "KG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "老挝",
            "code" : "LA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "拉脱维亚",
            "code" : "LV",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "黎巴嫩",
            "code" : "LB",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "莱索托",
            "code" : "LS",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "利比里亚",
            "code" : "LR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "列支敦士登",
            "code" : "LI",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "立陶宛",
            "code" : "LT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "马达加斯加",
            "code" : "MG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "马拉维",
            "code" : "MW",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "马来西亚",
            "code" : "MY",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "马尔代夫",
            "code" : "MV",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "马耳他",
            "code" : "MT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "毛里求斯",
            "code" : "MU",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "墨西哥",
            "code" : "MX",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "摩纳哥",
            "code" : "MC",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "蒙古",
            "code" : "MN",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "黑山",
            "code" : "ME",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "摩洛哥",
            "code" : "MA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "莫桑比克",
            "code" : "MZ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "缅甸",
            "code" : "MM",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "纳米比亚",
            "code" : "NA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "尼泊尔",
            "code" : "NP",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "新西兰",
            "code" : "NZ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "尼加拉瓜",
            "code" : "NI",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "尼日利亚",
            "code" : "NG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "挪威",
            "code" : "NO",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "阿曼",
            "code" : "OM",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "巴基斯坦",
            "code" : "PK",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "巴布亚新几内亚",
            "code" : "PG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "巴拉圭",
            "code" : "PY",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "秘鲁",
            "code" : "PE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "菲律宾",
            "code" : "PH",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "波兰",
            "code" : "PL",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "葡萄牙",
            "code" : "PT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "卡塔尔",
            "code" : "QA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "罗马尼亚",
            "code" : "RO",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "俄罗斯联邦",
            "code" : "RU",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "卢旺达",
            "code" : "RW",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "圣基茨和尼维斯",
            "code" : "KN",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "萨摩亚",
            "code" : "WS",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "圣多美和普林西比",
            "code" : "ST",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "沙特阿拉伯",
            "code" : "SA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "塞尔维亚",
            "code" : "RS",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "塞舌尔",
            "code" : "SC",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "塞拉利昂",
            "code" : "SL",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "新加坡",
            "code" : "SG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "斯洛文尼亚",
            "code" : "SI",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "南非",
            "code" : "ZA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "西班牙",
            "code" : "ES",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "斯里兰卡",
            "code" : "LK",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "苏丹",
            "code" : "SD",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "苏里南",
            "code" : "SR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "斯威士兰",
            "code" : "SZ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "瑞典",
            "code" : "SE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "瑞士",
            "code" : "CH",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "叙利亚",
            "code" : "SY",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "塔吉克斯坦",
            "code" : "TJ",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "泰国",
            "code" : "TH",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "汤加",
            "code" : "TO",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "特立尼达和多巴哥",
            "code" : "TT",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "突尼斯",
            "code" : "TN",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "土耳其",
            "code" : "TR",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "土库曼斯坦",
            "code" : "TM",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "乌干达",
            "code" : "UG",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "乌克兰",
            "code" : "UA",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "阿联酋",
            "code" : "AE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "英国",
            "code" : "GB",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "美国",
            "code" : "US",
            "prefix" : ""
        },

        {
            "regionID" : 4,
            "title" : "乌拉圭",
            "code" : "UY",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "乌兹别克斯坦",
            "code" : "UZ",
            "prefix" : ""
        },

        {
            "regionID" : 4,
            "title" : "委内瑞拉",
            "code" : "VE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "也门",
            "code" : "YE",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "赞比亚",
            "code" : "ZM",
            "prefix" : ""
        },
        {
            "regionID" : 4,
            "title" : "津巴布韦",
            "code" : "ZW",
            "prefix" : ""
        }];
        return regions;
    },

    getRegion : function(rid) {
        var list = this.getAllRegions();
        for ( var i = 0; i < list.length; i++) {
            if (list[i].regionID == rid)
                return list[i];
        }
        return null;
    },

    getRegionByCode : function(code) {
        var list = this.getAllRegions();
        for ( var i = 0; i < list.length; i++) {
            if (list[i].code == code)
                return list[i];
        }
        return null;
    },
    getRegionByTitle : function(title) {
        var list = this.getAllRegions();
        for ( var i = 0; i < list.length; i++) {
            if (list[i].title == title)
                return list[i];
        }
        return null;
    },
    getRegionByID : function(regionID) {
        var list = this.getAllRegions();
        for ( var i = 0; i < list.length; i++) {
            if (list[i].regionID == regionID)
                return list[i];
        }
        return null;
    }
};
/*****渲染国家****/
function renderRegionSelect(id){
    if(id==undefined){
        id=$('.country');
    }
    var regions = region.getAllRegions();
    for ( var i in regions) {
        var existcode = id.val();
        if(regions[i].code!=existcode){
            id.append('<option value="'+regions[i].code+'">'
            + regions[i].title + '</option>');
        }
    }
}
/*****渲染省份****/
function renderProvinceSelect(){
    var code = $('#country').val();
    var rg = region.getRegionByCode(code);
    $('#province').empty();
    $('#province').append('<option value="">-省份-</option>');
    renderCitySelect();
    if (rg == null) {
        return;
    }
    var provinces = region.getProvinces(rg.regionID);
    if(provinces.length<1){
        $("#province").hide();
        $("#city").hide();
    }else{
        $("#province").show();
        $("#city").show();
        for ( var i in provinces) {
            $("#province").append('<option value="'+provinces[i].title+'">'
            + provinces[i].title + '</option>');
        }
    }
}

/***渲染城市***/
function renderCitySelect(){
    if($('#city') == null){
        return;
    }
    var provinceTitle = $('#province').val();
    $('#city').empty();
    $('#city').append('<option value="">-城市-</option>');
    if (provinceTitle == "") {
        return;
    }
    var citys = region.getCityByProvinceTitle(provinceTitle);
    for ( var i in citys) {
        $('#city').append('<option value="'+citys[i].title+'">'
        + citys[i].title + '</option>');
    }

}