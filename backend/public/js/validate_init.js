$(function () {
    jQuery.validator.setDefaults({
        highlight: function (element) {
            console.log($(element));
            if ($(element).closest(".input-group").length) {
                $(element).closest(".input-group").addClass("has-error");
            } else if($('textarea').length > 0 ){
                    if($(element).parent().find('#cke_notice').length > 0){
                        $(element).parent().find('#cke_notice').addClass("has-error");
                    }else if($(element).parent().find('#cke_long_description').length > 0){
                        $(element).parent().find('#cke_long_description').addClass("has-error");
                    }else{
                        $(element).parent().addClass("has-error");
                    }
                    
            }else{
                $(element).parent().addClass("has-error");
            }
        },
        unhighlight: function (element) {
            if ($(element).closest(".input-group").length) {
                $(element).closest(".input-group").removeClass("has-error");
            } else {
                $(element).parent().removeClass("has-error");
            }
        },
        errorElement: "span",
        errorClass: "help-block",
        errorPlacement: function (error, element) {
            if (element.closest(".input-group").length) {
                if (
                    element.closest(".input-group").parent().find(".help-block")
                        .length
                ) {
                    element
                        .closest(".input-group")
                        .parent()
                        .find(".help-block")
                        .remove();
                }
                error.insertAfter(element.closest(".input-group"));
            } else {
                if (element.parent(".has-error").find(".help-block").length) {
                    element.parent(".has-error").find(".help-block").remove();
                }
                if($('textarea').length > 0 ){
                    if($(element).parent().find('#cke_notice').length > 0){
                        error.insertAfter(element.parent().find('#cke_long_description'));
                    }else if($(element).parent().find('#cke_long_description').length > 0){
                        error.insertAfter(element.parent().find('#cke_long_description'));
                    }else{
                        error.insertAfter(element);
                    }
                    
                }else{
                    error.insertAfter(element);
                }
                
            }
        },
        onfocusout: false,
    });

    $.validator.addMethod(
        "validatePostelcode",
        function (value, element) {
            var patt =
                /\b((?:(?:gir)|(?:[a-pr-uwyz])(?:(?:[0-9](?:[a-hjkpstuw]|[0-9])?)|(?:[a-hk-y][0-9](?:[0-9]|[abehmnprv-y])?)))) ?([0-9][abd-hjlnp-uw-z]{2})\b/i;

            return (res = patt.test(value));
        },
        "Invalid postel code."
    );

    function scrollOnError(validator) {
        $("html, body").animate(
            {
                scrollTop: $(validator.errorList[0].element).offset().top,
            },
            "slow",
            function () {
                validator.errorList[0].element.focus();
            }
        );
    }

    jQuery.validator.addMethod(
        "email",
        function (value, element) {
            return (
                this.optional(element) ||
                (/^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(
                    value
                ) &&
                    /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(value))
            );
        },
        "Invalid email address"
    );

    $.validator.addMethod("filesize", function (value, element, param) {
        // param = size (en bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || element.files[0].size <= param;
    });
   
    $.validator.addClassRules("cust-valid-basic-input-length", {
        required:true,
        maxlength: 200,
    },'Maximum 200 characters are allowed.');
    
    $.validator.addMethod(
        "phoneUK",
        function (phone_number, element) {
            return (
                this.optional(element) ||
                (phone_number.length > 9 &&
                    phone_number.match(
                        /^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/
                    ))
            );
        },
        "Please specify a valid phone number"
    );

    $.validator.addMethod(
        "alpha",
        function (value, element) {
            return (
                this.optional(element) || value == value.match(/^[a-zA-Z]+$/)
            );
        },
        "Only alphabets are allowed."
    );

    $.validator.addMethod("checklower", function (value) {
        return /[a-z]/.test(value);
    });
    $.validator.addMethod("checkupper", function (value) {
        return /[A-Z]/.test(value);
    });
    $.validator.addMethod("checkdigit", function (value) {
        return /[0-9]/.test(value);
    });
    $.validator.addMethod("checkdigit", function (value) {
        return /[0-9]/.test(value);
    });
    $.validator.addMethod("checkspecialchar", function (value) {
        return /[!@#$%^&*(),.?":{}|<>]/.test(value);
    });

    $.validator.addMethod(
        "reasonSpace",
        function (value, element) {
            return (
                this.optional(element) ||
                value == value.match(/^[^-\s][a-zA-Z0-9_\s-]+$/)
            );
        },
        "Please Enter Valid Reason."
    );

    $.validator.addMethod(
        "allowNumericWithDecimal",
        function (value, element) {
            return (
                this.optional(element) ||
                value == value.match(/^\+?[0-9]*\.?[0-9]+$/)
            );
        },
        "Please Enter only digits."
    );

    $.validator.addMethod(
        "alphabetsSpace",
        function (value, element) {
            return this.optional(element) || value.match(/^[a-zA-Z ]*$/);
        },
        "Only alphabets are allowed."
    );
    $("#web_login_form").validate({
        rules: {
            email: {
                required: true,
                email:true      
            },
            password: {
                required: true,
            }
        },
        messages: {
            email: {
                required: "Email is required.",
                email:'Invalid email address'
            },
            password: {
                required: "Password is required.",
            }
        }
    });

    // $("#web_forgot_form").validate({
    //     rules: {
    //         email: {
    //             required: true,
    //             email:true
    //         },
    //     },
    //     messages: {
    //         email: {
    //             required: "Email is required.",
    //             email:'Invalid email address'
    //         },
    //     }
    // });
    $("#web_register_form").validate({
        rules: {
            first_name: {
                required: true,
                maxlength: 255      
            },
            last_name: {
                required: true,
                maxlength: 255      
            },
            email: {
                required: true,
                maxlength: 100,
            },
            password:{
                required:true,
                minlength:2,
                maxlength:10,
            },
            phone_no: {
                required: true,
                maxlength:10,
            },
            address: {
                required: true,
                maxlength: 1000,
            },
            city_id: {
                required: true,
            },
            state_id: {
                required: true,
            },
            country_id: {
                required: true,
            },
            pincode:{
                required: true,
            }
        },
        messages: {
            first_name: {
                required: "First name is required.",
                maxlength: "Maximum 255 characters are allowed."
            },
            last_name: {
                required: "Last name is required.",
                maxlength: "Maximum 255 characters are allowed."
            },
            email: {
                required: "Email is required.",
                maxlength: "Maximum 100 characters are allowed."
            },
            password: {
                required: "Password is required.",
                minlength: "Minimum 2 characters are required",
                maxlength: "Maximum 10 characters are allowed."
            },
            phone_no: {
                required: "Phone number is required.",
                maxlength: "Maximum 10 digits are allowed."
            },
            address: {
                required: "Address is required.",
                maxlength: "Maximum 1000 characters are allowed."
            },
            city_id: {
                required: "City is required.",
            },
            state_id: {
                required: "State is required.",
            },
            country_id: {
                required: "Country is required.",
            },
            pincode:{
                required: 'Pincode is required.'
            }
        }
    });
    $("#forgot_form").validate({
        rules: {
            email: {
                required: true,
                email:true
            },
        },
        messages: {
            email: {
                required: "Email is required.",
                email:'Invalid email address'
            },
        }
    });
    $("#reset_form").validate({
        rules: {
            email: {
                required: true,
                email:true
            },
            password: {
                required: true,
                minlength:2,
                maxlength:10,
            },
            password_confirmation:{
                equalTo: "#password",
            },
        },
        messages: {
            email: {
                required: "Email is required.",
                email:'Invalid email address'
            },
            password:{
                required: "Password is required.",
                minlength:'Minimum 2 characters are required.',
                maxlength:'Maximum 10 characters are allowed.',
            },
            password_confirmation: {
                equalTo:'Confirm password must match with your password.'
            },
        },
    })
    $("#contact_us").validate({
        rules: {
            email: {
                required: true,
                email:true
            },
            first_name: {
                required: true,
                maxlength:200,
            },
            last_name:{
                required: true,
                maxlength:200,
            },
            phone_no:{
                required: true,
                maxlength:10,
            },
            message:{
                maxlength:1000,
            },
        },
        messages: {
            email: {
                required: "Email is required.",
                email:'Invalid email address'
            },
            first_name:{
                required: "First name is required.",
                maxlength:'Maximum 200 characters are allowed.',
            },
            last_name: {
                required: "Last name is required.",
                maxlength:'Maximum 200 characters are allowed.',
            },
            phone_no: {
                required: "Phone number is required.",
                maxlength:'Maximum 10 digits are allowed.',
            },
            message: {
                maxlength:'Maximum 1000 characters are allowed.',
            },
        },
    })
    $("#frm_profile").validate({
        rules: {
            email: {
                required: true,
                email:true
            },
            first_name: {
                required: true,
                maxlength:200,
            },
            last_name:{
                required: true,
                maxlength:200,
            },
            phone_no:{
                required: true,
                maxlength:10,
            },
            date_of_birth:{
                required: true,
            },
            gender:{
                required:true,
            },
            address:{
                required: true,
            },
            father_name:{
                required:true,
            },
            'title[]':{
                required:true,
            }, 
            'qualification[]':{
                required:true,
                maxlength:1000,
            }
        },
        messages: {
            email: {
                required: "Email is required.",
                email:'Invalid email address'
            },
            first_name:{
                required: "First name is required.",
                maxlength:'Maximum 200 characters are allowed.',
            },
            last_name: {
                required: "Last name is required.",
                maxlength:'Maximum 200 characters are allowed.',
            },
            phone_no: {
                required: "Phone number is required.",
                maxlength:'Maximum 10 digits are allowed.',
            },
            date_of_birth:{
                required: "Date of birth is required.",
            },
            gender:{
                required: "Gender is required.",
            },
            address:{
                required: "Address is required.",
                maxlength:'Maximum 1000 characters are allowed.',
            },
            father_name:{
                required: "Father name is required.",
                maxlength:'Maximum 255 characters are allowed.',
            },
            'title[]':{
                required: "Title is required.",
                maxlength:'Maximum 255 characters are allowed.',
            }, 
            'qualification[]':{
                required: "Qualification description is required.",
                maxlength:'Maximum 1000 characters are allowed.',
            }
        },
    })
});
