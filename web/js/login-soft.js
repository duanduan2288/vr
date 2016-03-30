var Login = function () {

	var handleLogin = function() {
		$('.login-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                username: {
	                    required: true
	                },
	                password: {
	                    required: true
	                },
	                remember: {
	                    required: false
	                },
                    verifyCode: {
                        required: true
                    }
	            },

	            messages: {
	                username: {
	                    required: "请输入用户名"
	                },
	                password: {
	                    required: "请输入密码"
	                },
                    verifyCode: {
                        required: "请输入验证码"
                    }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-danger', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {

	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

	        $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
	                    $('.login-form').submit();
	                }
	                return false;
	            }
	        });
        $('.login-form .checker').click(function () {
            $(this).find('span').addClass('checked');
            $(this).find('input').attr('checked',true);
            $(this).closest('.radio-inline').siblings().find('.checker span').removeClass('checked');
            $(this).closest('.radio-inline').siblings().find('.checker input').attr('checked',false);

        });
	}

	var handleForgetPassword = function () {
		$('.forget-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                email: {
	                    required: true,
	                    email: true
	                },
                    verifyCode: {
                        required: true
                    }
	            },

	            messages: {
	                email: {
                        required: "请输入注册邮箱"
                    },
                    verifyCode: {
                        required: "请输入验证码"
                    }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

	        $('.forget-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.forget-form').validate().form()) {
	                    $('.forget-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#forget-password').click(function () {
                history.go(-1);
//	            jQuery('.login-form').hide();
//	            jQuery('.forget-form').show();
	        });

	        jQuery('#back-btn').click(function () {
                history.go(-1);
//	            jQuery('.login-form').show();
//	            jQuery('.forget-form').hide();
	        });

	}

	var handleRegister = function () {

		function format(state) {
            if (!state.id) return state.text; // optgroup
            return "<img class='flag' src='assets/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
        }


		$("#select2_sample4").select2({
		  	placeholder: '<i class="fa fa-map-marker"></i>&nbsp;Select a Country',
            allowClear: true,
            formatResult: format,
            formatSelection: format,
            escapeMarkup: function (m) {
                return m;
            }
        });


			$('#select2_sample4').change(function () {
                $('.register-form').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });



         $('.register-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            ignore: "",
	            rules: {
	                
	                fullname: {
	                    required: true
	                },
	                email: {
	                    required: true,
	                    email: true,
                        remote:{
                            url:'/site/checkemail',
                            type:'post',
                            data:{
                                'email':function(){
                                    return $('#email').val();
                                }
                            }
                        }
	                },
	                username: {
	                    required: true,
                        remote:{
                            url:'/site/checkusername',
                            type:'post',
                            data:{
                                'username':function(){
                                    return $('#username').val();
                                }
                            }
                        }
	                },
                    password: {
                        required: true,
                        remote:{
                            url:'/site/checkpassword',
                            type:'post',
                            data:{
                                'password':function(){
                                    return $('#password').val();
                                }
                            }
                        }
                    },
	                rpassword: {
	                    equalTo: "#register_password"
	                },

	                tnc: {
	                    required: true
	                }
	            },

	            messages: { // custom messages for radio buttons and checkboxes
	                tnc: {
	                    required: "请选择是否接受"
	                },
                    username: {
                        required: "请输入用户名",
                        remote:'该用户名已存在'
                    },
                    email: {
                        required: "请输入邮箱",
                        remote:'该Emai已注册'
                    },
                     password: {
                         required: "请输入密码",
                         remote:'密码必须包含大写字母、小写字母、数字'
                     },
                    rpassword:{
                        equalTo:'密码不一致',
                        required:'请确认密码'
                    }

	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   

	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                if (element.attr("name") == "tnc") { // insert checkbox errors after the container                  
	                    error.insertAfter($('#register_tnc_error'));
	                } else if (element.closest('.input-icon').size() === 1) {
	                    error.insertAfter(element.closest('.input-icon'));
	                } else {
	                	error.insertAfter(element);
	                }
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

			$('.register-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.register-form').validate().form()) {
	                    $('.register-form').submit();
	                }
	                return false;
	            }
	        });

	        jQuery('#register-btn').click(function () {
                history.go(-1);
//	            jQuery('.login-form').hide();
//	            jQuery('.register-form').show();
	        });

	        jQuery('#register-back-btn').click(function () {
                history.go(-1);
//	            jQuery('.login-form').show();
//	            jQuery('.register-form').hide();
	        });
            jQuery('.alert-danger .close').click(function () {
                $(this).closest('.alert-danger').hide();
            });
	}

    var handleresetPassword = function () {
        $('.reset-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",
            rules: {
                password: {
                    required: true
                },
                rpassword: {
                    required: true
                }
            },
            messages: {
                password: {
                    required: "请输入新密码"
                },
                rpassword: {
                    required: "请再次输入密码"
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit

            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            submitHandler: function (form) {
                form.submit();
            }
        });

        $('.reset-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.reset-form').validate().form()) {
                    $('.reset-form').submit();
                }
                return false;
            }
        });

    }
    return {
        //main function to initiate the module
        init: function () {
        	
            handleLogin();
            handleForgetPassword();
            handleRegister();
            handleresetPassword();
	       	//$.backstretch([
		    //    "/img/bg/1.jpg",
		    //    "/img/bg/2.jpg",
		    //    "/img/bg/3.jpg",
		    //    "/img/bg/4.jpg"
		    //    ], {
		    //      fade: 1000,
		    //      duration: 8000
		    //});
        }

    };

}();