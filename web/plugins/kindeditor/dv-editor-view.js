
KindEditor.ready(function(KE) {
    var uploadimg='/upload/uploadimage';
    var imgmanager='/upload/manageimages';
    KE.create('.dv-editor', {
        uploadJson : uploadimg,
        fileManagerJson : imgmanager,
        allowFileManager : true,
        width: '100%',
        height : "200px",
        minWidth: "300",
        items : ['fontname', 'fontsize',  'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat',  'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                'insertunorderedlist',  'emoticons', 'image', 'link', 'source', 'fullscreen']
    });

    //<input type="text" class="hide" readonly name="img" value="" /><input type="button" class="btn btn-default dv-uploadimg" module="image" value="选择图片" />
    var $thumb_tpl='<img class="thumb_img" src="{url}" style="max-height: 100px;max-width:200px">';
    var editor = KE.editor({
        themeType: "simple",
        uploadJson : uploadimg,
        fileManagerJson : imgmanager,
        allowFileManager : true
    });
    //add by ryq 上传图片弹出框位置居中显示的function
    function resetPos(curDialog) {
                var left, top;
                var $curDialogs = $(curDialog.div);
                if(curDialog ){
                    //var pageSidebar = $('.page-sidebar');
                    //padding-left:225px
                    //debugger;
                    // var clwidth = $curDialogs.width(),
                    //     defWidth = parent.document.documentElement.clientWidth - $curDialogs.width(),
                    //     defWidth2 = (parent.document.documentElement.clientWidth - $curDialogs.width()) / 2,
                    //     setwidth = (defWidth<=clwidth) ? clwidth/2 : defWidth2,

                    var setwidth = (parent.document.documentElement.clientWidth - $curDialogs.width()) / 2,
                    //     setheight = ($(parent.window).height() - parent.document.documentElement.clientHeight) / 2;
                    //var setwidth = (parent.document.documentElement.clientWidth - $curDialogs.width()) / 2,
                        //setheight = (parent.document.documentElement.clientHeight - $curDialogs.height()) / 2;

                        clheight = $curDialogs.height(),
                        defHeight = parent.document.documentElement.clientHeight - $curDialogs.height(),
                        defHeight2 = (parent.document.documentElement.clientHeight - $curDialogs.height()) / 2,
                        //setheight = (defHeight<=clheight) ? clheight/2 : defHeight2;
                        setheight = (defHeight<=0) ? 0 : defHeight2;
                        left = setwidth;
                        //left = $(parent.document).scrollLeft()>0 ? setwidth+$(parent.document).scrollLeft() : setwidth;
                        top = $(parent.document).scrollTop()>0 ? setheight+$(parent.document).scrollTop() : setheight;
                
                    $curDialogs.css({
                        top: top + "px",
                        left: left + "px"
                    });
                }
                
            }
            //add by ryq 上传图片弹出框位置居中显示的function end
             //ryq add
        var divimg = $('.J_imageView');
        //div.html('');
        divimg.each(function(){
            
            var singleEL = $(this).find('.exchange_ul');

            if(singleEL.length>0){
                var oEl = X.Exchange(singleEL);
                    oEl.serialize();
            }else{
                singleEL = $('<ul class="exchange_ul"><input type="hidden" class="seria" /></ul>');
                $(this).append(singleEL);
            }
            
        });
        
        
        //ryq add end
    KE('.dv-uploadimg').click(function(e) {
       


        var module;
        if($(this).attr('module')){
            module=$(this).attr('module');
        }else{
            module=self.location.href.split( "/" )[3]
        }

        editor.uploadJson=uploadimg+'?module='+module+'&aid='+aid;
        editor.loadPlugin('dv-image', function() {
            //debugger;
            var $input=$(e.target).prevAll("input[type=text]");
            var $thumb=$(e.target).prevAll(".thumb_img:first");
            var curEditorDialog = editor.plugin.imageDialog({
                imageUrl : $input.val(),
                clickFn : function(url, title, width, height, border, align) {
                    //debugger;
                    $input.val(url);
                    $input.trigger('change');
                    $input.trigger('focusout');
                    if ($thumb.length == 0) {
                        $input.before($thumb_tpl.replace('{url}',url))
                    } else {
                        $thumb.attr("src", url);
                    }
                    
                    // var str = ' <li>\
                    //                 <div class="">\
                    //                     <span data-control="left"><i class="icon-arrow-left"></i></span>\
                    //                     <span data-control="right"><i class="icon-arrow-right"></i></span>\
                    //                     <span data-control="delete"><i class="icon-trash"></i></span>\
                    //                 </div>\
                    //                 <img src="' + url + '" data-name="'+ url +'">\
                    //             </li>';
                                             
                    //             singleEL.append($(str));
                    //             oEl.serialize();
                    
                                editor.hideDialog();
                }
            });


            
            //add by ryq 上传图片弹出框位置居中显示
            
            $(parent.document).scrollTop(0);//初始化scrollTop
            resetPos(curEditorDialog);

            $(parent.window).scroll(function () {
                resetPos(curEditorDialog);
                return false;
            });
            $(parent.window).on("resize", function () {   
                resetPos(curEditorDialog);
            });

            // console.log("App:");
            // console.log(App);
            // if (App.getResize()) {
            //     editor.dialogs[0].pos();
            //         //App.getResize();
            // }

            //add by ryq 上传图片弹出框位置居中显示 end

        });
    });

    

    KE('.J_selectImage').click(function(e) {//批量上传

        //editor.uploadJson=uploadimg+'?module='+module;
        
            var div = $(this).closest('div').find('.J_imageView');
            //div.html('');
            // var uEL = $('<ul class="exchange_ul"><input type="hidden" class="seria" /></ul>');
            //     div&&div.append(uEL);
            uEL = div.find('.exchange_ul');
            
                var oTest = X&&X.Exchange(uEL);
                oTest&&oTest.serialize();
                    var module;
        if($(this).attr('module')){
            module=$(this).attr('module');
        }else{
            module=self.location.href.split( "/" )[3]
        }

        editor.uploadJson=uploadimg+'?module='+module+'&aid='+aid;
        editor.loadPlugin('multiimage', function() {
            var curMultiImageDialog = editor.plugin.multiImageDialog({
                clickFn : function(urlList) {
                    if(module=='weisite'){
                        var uELLi = uEL.find('li'),
                            allowNum = 5;
                        if(uELLi.length+urlList.length>5){
                            alert('您已上传了'+uELLi.length+'张，还可以上传'+(5-uELLi.length)+'张！');
                            return;
                        }
                    }
                    $("#J_selectImage_tools").size() && $("#J_selectImage_tools").show();
                    KE.each(urlList, function(i, data) {
                            // if(i>allowNum-1){
                            //     //alert('最多允许上传5张图片！');
                            //     return;
                            // }
                            var str = ' <li>\
                                            <div class="">\
                                                <span data-control="left"><i class="icon-arrow-left"></i></span>\
                                                <span data-control="right"><i class="icon-arrow-right"></i></span>\
                                                <span data-control="delete"><i class="icon-trash"></i></span>\
                                            </div>\
                                            <img src="' + data.url + '" data-name="'+ data.url +'">\
                                        </li>';
                                             
                                uEL&&uEL.append($(str));
                                

                        //
                    });
                    
                        oTest = X&&X.Exchange(uEL);
                        oTest&&oTest.serialize();
                        //oTest.resetOpt;
                    

                    editor.hideDialog();
                }
            });

            //add by ryq 批量上传弹出框位置居中显示
            
            $(parent.document).scrollTop(0);//初始化scrollTop
            resetPos(curMultiImageDialog);

            $(parent.window).scroll(function () {
                resetPos(curMultiImageDialog);
                return false;
            });
            $(parent.window).on("resize", function () {   
                resetPos(curMultiImageDialog);
            });

            //add by ryq 批量上传弹出框位置居中显示 end


        });
    });




    



    $('.dv-uploadimg').each(function(){
        var $input = $(this).prevAll("input[type=text]");
        var $thumb=$(this).prevAll(".thumb_img:first");
        if($input.val().length>1){
            if ($thumb.length == 0) {
                $input.before($thumb_tpl.replace('{url}',$input.val()))
            } else {
                $thumb.attr("src", $input.val());
            }
        }
    });

});