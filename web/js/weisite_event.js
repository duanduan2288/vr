
    var _choose_init;
    var _overall_choose_init;
    var _album_choose_init;
    var _meeting_choose_init;
	var _store_choose_init;
	var _reserve_choose_init;
	var _message_choose_init;
	var _survey_choose_init;
	var _invite_choose_init;
    $(document).ready(function(){
        var _isoso = false;
        $("#_soso").click(function () { _isoso = true; $("#_soso").attr("disabled", ""); _page(1) });
        $("#classid").change(function () { _this_page = 1; _page(1) });
        $("#data-list input[name='radio']").live("click", function () {
            var ck = $(this);
            var _tmp = '<span class="maroon">图文标题: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="article_id" value="{1}" />';
            $("div.choose").html(_tmp.format(ck.data("title"), ck.val()));
        });
        //删除
        $("a.ed_choose").live("click", function (e) {
            e.preventDefault();
            article_choose_init();
        });
        function article_choose_init() {
            $('#article_choose').modal('show');
            _page(1);
        };
        _choose_init = function () {
            article_choose_init();
        }
        $("#p_page").click(function () {
            if (_this_page - 1 > 0) {
                _this_page--;
                _page(_this_page);
            }
        });
        $("#n_page").click(function () {
            if (_this_page + 1 <= _this_page_count) {
                _this_page++;
                _page(_this_page);
            }
        });
        var _this_page = 1;//当前页
        var _this_page_count = 0;//总页数
        var _page = function (_index) {
            var key;
            if (_isoso) {
                key = $("#key").val();
            }
            var classid = $("#classid").val();
            $.get("/weisite/uc/slide/Votetouser", {"key": key, "classid": classid, "page": _index }, function (data, textStatus) {
                $("#data-list").html("");
                $.each(data.list, function (index, item) {
                    var _li_tmp = '<li> <label> <input type="radio" name="radio" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#data-list").append(_li_tmp);
                });
                _this_page_count = data.pagenum;
                $("#count_num").text(_this_page_count);
                $("#p_page_str").text("第" + _this_page + "页/共" + Math.ceil(_this_page_count/9) + "页");
                $("#_soso").removeAttr("disabled")
            }, "json");
        }



        var _overall_isoso = false;
        $("#_overall_soso").click(function () { _overall_isoso = true; $("#_overall_soso").attr("disabled", ""); _page1(1) });
        //$("#overall_classid").change(function () { overall_this_page = 1; _page1(1) });
        $("#overall_data-list input[name='overall_id']").live("click", function () {
            var overall_ck = $(this);
            var overall_tmp = '<span class="maroon">360全景分类: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_overall_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[overall_id]" value="{1}" />'
            $("div.overall_choose").html(overall_tmp.format(overall_ck.data("title"), overall_ck.val()));
        });
        //删除
        $("a.ed_overall_choose").live("click", function (e) {
            e.preventDefault();
            overall_choose_init();
        });
        function overall_choose_init() {
            $('#overall_choose').modal('show');
            _page1(1);
        };
        _overall_choose_init = function () {
            overall_choose_init();
        }
        $("#overall_p_page").click(function () {
            if (overall_this_page - 1 > 0) {
                overall_this_page--;
                _page1(overall_this_page);
            }
        });
        $("#overall_n_page").click(function () {
            if (overall_this_page + 1 <= _this_page_count) {
                overall_this_page++;
                _page1(overall_this_page);
            }
        });
        var overall_this_page = 1;//当前页
        var _this_page_count = 0;//总页数
        var _page1 = function (_index) {
            var overall_key;
            if (_overall_isoso) {
                overall_key = $("#overall_key").val();
            }
            //var overall_classid = $("#overall_classid").val();
            $.get("/weisite/uc/slide/overall", {"key": overall_key, "page": _index }, function (data, textStatus) {
                $("#overall_data-list").html("");
                $.each(data.list, function (index, item) {
                    var overall_li_tmp = '<li> <label> <input type="radio" name="overall_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#overall_data-list").append(overall_li_tmp);
                });
                _this_page_count = data.pagenum;
                $("#overall_count_num").text(_this_page_count);
                $("#overall_p_page_str").text("第" + overall_this_page + "页/共" + Math.ceil(_this_page_count/9) + "页");
                $("#_overall_soso").removeAttr("disabled")
            }, "json");
        }


        var _album_isoso = false;
        $("#_album_soso").click(function () { _album_isoso = true; $("#_album_soso").attr("disabled", ""); _album_page(1) });
        //$("#album_classid").change(function () { album_this_page = 1; _album_page(1) });
        $("#album_data-list input[name='album_id']").live("click", function () {
            var album_ck = $(this);
            var album_tmp = '<span class="maroon">微相册专辑: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_album_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[album_id]" value="{1}" />'
            $("div.album_choose").html(album_tmp.format(album_ck.data("title"), album_ck.val()));
        });
        //删除
        $("a.ed_album_choose").live("click", function (e) {
            e.preventDefault();
            album_choose_init();
        });
        function album_choose_init() {
            $('#album_choose').modal('show');
            _album_page(1);
        };
        _album_choose_init = function () {
            album_choose_init();
        }
        $("#album_p_page").click(function () {
            if (album_this_page - 1 > 0) {
                album_this_page--;
                _album_page(album_this_page);
            }
        });
        $("#album_n_page").click(function () {
            if (album_this_page + 1 <= album_this_page_count) {
                album_this_page++;
                _album_page(album_this_page);
            }
        });
        var album_this_page = 1;//当前页
        var album_this_page_count = 0;//总页数
        var _album_page = function (_index) {
            var album_key;
            if (_album_isoso) {
                album_key = $("#album_key").val();
            }
            //var album_classid = $("#album_classid").val();
            $.get("/weisite/uc/slide/album", {"key": album_key, "page": _index }, function (data, textStatus) {
                $("#album_data-list").html("");
                $.each(data.list, function (index, item) {
                    var album_li_tmp = '<li> <label> <input type="radio" name="album_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#album_data-list").append(album_li_tmp);
                });
                album_this_page_count = data.pagenum;
                $("#album_count_num").text(album_this_page_count);
                $("#album_p_page_str").text("第" + album_this_page + "页/共" + Math.ceil(album_this_page_count/9) + "页");
                $("#_album_soso").removeAttr("disabled")
            }, "json");
        }




        var _meeting_isoso = false;
        $("#_meeting_soso").click(function () { _meeting_isoso = true; $("#_meeting_soso").attr("disabled", ""); _meeting_page(1) });
        //$("#meeting_classid").change(function () { meeting_this_page = 1; _meeting_page(1) });
        $("#meeting_data-list input[name='meeting_id']").live("click", function () {
            var meeting_ck = $(this);
            var meeting_tmp = '<span class="maroon">微会议: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_meeting_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[meeting_id]" value="{1}" />'
            $("div.meeting_choose").html(meeting_tmp.format(meeting_ck.data("title"), meeting_ck.val()));
        });
        //删除
        $("a.ed_meeting_choose").live("click", function (e) {
            e.preventDefault();
            meeting_choose_init();
        });
        function meeting_choose_init() {
            $('#meeting_choose').modal('show');
            _meeting_page(1);
        };
        _meeting_choose_init = function () {
            meeting_choose_init();
        }
        $("#meeting_p_page").click(function () {
            if (meeting_this_page - 1 > 0) {
                meeting_this_page--;
                _meeting_page(meeting_this_page);
            }
        });
        $("#meeting_n_page").click(function () {
            if (meeting_this_page + 1 <= meeting_this_page_count) {
                meeting_this_page++;
                _meeting_page(meeting_this_page);
            }
        });
        var meeting_this_page = 1;//当前页
        var meeting_this_page_count = 0;//总页数
        var _meeting_page = function (_index) {
            var meeting_key;
            if (_meeting_isoso) {
                meeting_key = $("#meeting_key").val();
            }
            //var meeting_classid = $("#meeting_classid").val();
            $.get("/weisite/uc/slide/meeting", {"key": meeting_key, "page": _index }, function (data, textStatus) {
                $("#meeting_data-list").html("");
                $.each(data.list, function (index, item) {
                    var meeting_li_tmp = '<li> <label> <input type="radio" name="meeting_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#meeting_data-list").append(meeting_li_tmp);
                });
                meeting_this_page_count = data.pagenum;
                $("#meeting_count_num").text(meeting_this_page_count);
                $("#meeting_p_page_str").text("第" + meeting_this_page + "页/共" + Math.ceil(meeting_this_page_count/9) + "页");
                $("#_meeting_soso").removeAttr("disabled")
            }, "json");
        }

		var _store_isoso = false;
        $("#_store_soso").click(function () { _store_isoso = true; $("#_store_soso").attr("disabled", ""); _store_page(1) });
        //$("#store_classid").change(function () { store_this_page = 1; _store_page(1) });
        $("#store_data-list input[name='store_id']").live("click", function () {
            var store_ck = $(this);
            var store_tmp = '<span class="maroon">门店: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_store_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[store_id]" value="{1}" />'
            $("div.store_choose").html(store_tmp.format(store_ck.data("title"), store_ck.val()));
        });
        //删除
        $("a.ed_store_choose").live("click", function (e) {
            e.preventDefault();
            store_choose_init();
        });
        function store_choose_init() {
            $('#store_choose').modal('show');
            _store_page(1);
        };
        _store_choose_init = function () {
            store_choose_init();
        }
        $("#store_p_page").click(function () {
            if (store_this_page - 1 > 0) {
                store_this_page--;
                _store_page(store_this_page);
            }
        });
        $("#store_n_page").click(function () {
            if (store_this_page + 1 <= store_this_page_count) {
                store_this_page++;
                _store_page(store_this_page);
            }
        });
        var store_this_page = 1;//当前页
        var store_this_page_count = 0;//总页数
        var _store_page = function (_index) {
            var store_key;
            if (_store_isoso) {
                store_key = $("#store_key").val();
            }
            //var store_classid = $("#store_classid").val();
            $.get("/weisite/uc/slide/store", {"key": store_key, "page": _index }, function (data, textStatus) {
                $("#store_data-list").html("");
                $.each(data.list, function (index, item) {
                    var store_li_tmp = '<li> <label> <input type="radio" name="store_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#store_data-list").append(store_li_tmp);
                });
                store_this_page_count = data.pagenum;
                $("#store_count_num").text(store_this_page_count);
                $("#store_p_page_str").text("第" + store_this_page + "页/共" + Math.ceil(store_this_page_count/9) + "页");
                $("#_store_soso").removeAttr("disabled")
            }, "json");
        }


		var _reserve_isoso = false;
        $("#_reserve_soso").click(function () { _reserve_isoso = true; $("#_reserve_soso").attr("disabled", ""); _reserve_page(1) });
        //$("#reserve_classid").change(function () { reserve_this_page = 1; _reserve_page(1) });
        $("#reserve_data-list input[name='reserve_id']").live("click", function () {
            var reserve_ck = $(this);
            var reserve_tmp = '<span class="maroon">微预约: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_reserve_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[reserve_id]" value="{1}" />'
            $("div.reserve_choose").html(reserve_tmp.format(reserve_ck.data("title"), reserve_ck.val()));
        });
        //删除
        $("a.ed_reserve_choose").live("click", function (e) {
            e.preventDefault();
            reserve_choose_init();
        });
        function reserve_choose_init() {
            $('#reserve_choose').modal('show');
            _reserve_page(1);
        };
        _reserve_choose_init = function () {
            reserve_choose_init();
        }
        $("#reserve_p_page").click(function () {
            if (reserve_this_page - 1 > 0) {
                reserve_this_page--;
                _reserve_page(reserve_this_page);
            }
        });
        $("#reserve_n_page").click(function () {
            if (reserve_this_page + 1 <= reserve_this_page_count) {
                reserve_this_page++;
                _reserve_page(reserve_this_page);
            }
        });
        var reserve_this_page = 1;//当前页
        var reserve_this_page_count = 0;//总页数
        var _reserve_page = function (_index) {
            var reserve_key;
            if (_reserve_isoso) {
                reserve_key = $("#reserve_key").val();
            }
            //var reserve_classid = $("#reserve_classid").val();
            $.get("/weisite/uc/slide/reserve", {"key": reserve_key, "page": _index }, function (data, textStatus) {
                $("#reserve_data-list").html("");
                $.each(data.list, function (index, item) {
                    var reserve_li_tmp = '<li> <label> <input type="radio" name="reserve_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#reserve_data-list").append(reserve_li_tmp);
                });
                reserve_this_page_count = data.pagenum;
                $("#reserve_count_num").text(reserve_this_page_count);
                $("#reserve_p_page_str").text("第" + reserve_this_page + "页/共" + Math.ceil(reserve_this_page_count/9) + "页");
                $("#_reserve_soso").removeAttr("disabled")
            }, "json");
        }
        
        var _invite_isoso = false;
        $("#_invite_soso").click(function () { _invite_isoso = true; $("#_invite_soso").attr("disabled", ""); _invite_page(1) });
        //$("#invite_classid").change(function () { invite_this_page = 1; _invite_page(1) });
        $("#invite_data-list input[name='invite_id']").live("click", function () {
            var invite_ck = $(this);
            var invite_tmp = '<span class="maroon">微预约: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_invite_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[invite_id]" value="{1}" />'
            $("div.invite_choose").html(invite_tmp.format(invite_ck.data("title"), invite_ck.val()));
        });
        //删除
        $("a.ed_invite_choose").live("click", function (e) {
            e.preventDefault();
            invite_choose_init();
        });
        function invite_choose_init() {
            $('#invite_choose').modal('show');
            _invite_page(1);
        };
        _invite_choose_init = function () {
            invite_choose_init();
        }
        $("#invite_p_page").click(function () {
            if (invite_this_page - 1 > 0) {
                invite_this_page--;
                _invite_page(invite_this_page);
            }
        });
        $("#invite_n_page").click(function () {
            if (invite_this_page + 1 <= invite_this_page_count) {
                invite_this_page++;
                _invite_page(invite_this_page);
            }
        });
        var invite_this_page = 1;//当前页
        var invite_this_page_count = 0;//总页数
        var _invite_page = function (_index) {
            var invite_key;
            if (_invite_isoso) {
                invite_key = $("#invite_key").val();
            }
            //var invite_classid = $("#invite_classid").val();
            $.get("/weisite/uc/slide/invite", {"key": invite_key, "page": _index }, function (data, textStatus) {
                $("#invite_data-list").html("");
                $.each(data.list, function (index, item) {
                    var invite_li_tmp = '<li> <label> <input type="radio" name="invite_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#invite_data-list").append(invite_li_tmp);
                });
                invite_this_page_count = data.pagenum;
                $("#invite_count_num").text(invite_this_page_count);
                $("#invite_p_page_str").text("第" + invite_this_page + "页/共" + Math.ceil(invite_this_page_count/9) + "页");
                $("#_invite_soso").removeAttr("disabled")
            }, "json");
        }

		var _message_isoso = false;
        $("#_message_soso").click(function () { _message_isoso = true; $("#_message_soso").attr("disabled", ""); _message_page(1) });
        //$("#message_classid").change(function () { message_this_page = 1; _message_page(1) });
        $("#message_data-list input[name='message_id']").live("click", function () {
            var message_ck = $(this);
            var message_tmp = '<span class="maroon">微留言: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_message_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[message_id]" value="{1}" />'
            $("div.message_choose").html(message_tmp.format(message_ck.data("title"), message_ck.val()));
        });
        //删除
        $("a.ed_message_choose").live("click", function (e) {
            e.preventDefault();
            message_choose_init();
        });
        function message_choose_init() {
            $('#message_choose').modal('show');
            _message_page(1);
        };
        _message_choose_init = function () {
            message_choose_init();
        }
        $("#message_p_page").click(function () {
            if (message_this_page - 1 > 0) {
                message_this_page--;
                _message_page(message_this_page);
            }
        });
        $("#message_n_page").click(function () {
            if (message_this_page + 1 <= message_this_page_count) {
                message_this_page++;
                _message_page(message_this_page);
            }
        });
        var message_this_page = 1;//当前页
        var message_this_page_count = 0;//总页数
        var _message_page = function (_index) {
            var message_key;
            if (_message_isoso) {
                message_key = $("#message_key").val();
            }
            //var message_classid = $("#message_classid").val();
            $.get("/weisite/uc/slide/message", {"key": message_key, "page": _index }, function (data, textStatus) {
                $("#message_data-list").html("");
                $.each(data.list, function (index, item) {
                    var message_li_tmp = '<li> <label> <input type="radio" name="message_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#message_data-list").append(message_li_tmp);
                });
                message_this_page_count = data.pagenum;
                $("#message_count_num").text(message_this_page_count);
                $("#message_p_page_str").text("第" + message_this_page + "页/共" + Math.ceil(message_this_page_count/9) + "页");
                $("#_message_soso").removeAttr("disabled")
            }, "json");
        }



		var _survey_isoso = false;
        $("#_survey_soso").click(function () { _survey_isoso = true; $("#_survey_soso").attr("disabled", ""); _survey_page(1) });
        //$("#survey_classid").change(function () { survey_this_page = 1; _survey_page(1) });
        $("#survey_data-list input[name='survey_id']").live("click", function () {
            var survey_ck = $(this);
            var survey_tmp = '<span class="maroon">微调研: {0} </span><span class="help-inline"><a href="javascript:void(0)" class="btn btn-mini ed_survey_choose"><i class="icon-edit"></i></a></span>  <input type="hidden" name="WeisiteEvent[survey_id]" value="{1}" />'
            $("div.survey_choose").html(survey_tmp.format(survey_ck.data("title"), survey_ck.val()));
        });
        //删除
        $("a.ed_survey_choose").live("click", function (e) {
            e.preventDefault();
            survey_choose_init();
        });
        function survey_choose_init() {
            $('#survey_choose').modal('show');
            _survey_page(1);
        };
        _survey_choose_init = function () {
            survey_choose_init();
        }
        $("#survey_p_page").click(function () {
            if (survey_this_page - 1 > 0) {
                survey_this_page--;
                _survey_page(survey_this_page);
            }
        });
        $("#survey_n_page").click(function () {
            if (survey_this_page + 1 <= survey_this_page_count) {
                survey_this_page++;
                _survey_page(survey_this_page);
            }
        });
        var survey_this_page = 1;//当前页
        var survey_this_page_count = 0;//总页数
        var _survey_page = function (_index) {
            var survey_key;
            if (_survey_isoso) {
                survey_key = $("#survey_key").val();
            }
            //var survey_classid = $("#survey_classid").val();
            $.get("/weisite/uc/slide/survey", {"key": survey_key, "page": _index }, function (data, textStatus) {
                $("#survey_data-list").html("");
                $.each(data.list, function (index, item) {
                    var survey_li_tmp = '<li> <label> <input type="radio" name="survey_id" data-title="' + item.title + '" value="' + item.id + '" />  ' + item.title + '</label></li>';
                    $("#survey_data-list").append(survey_li_tmp);
                });
                survey_this_page_count = data.pagenum;
                $("#survey_count_num").text(survey_this_page_count);
                $("#survey_p_page_str").text("第" + survey_this_page + "页/共" + Math.ceil(survey_this_page_count/9) + "页");
                $("#_survey_soso").removeAttr("disabled")
            }, "json");
        }
    })
