$(function () {
    jQuery.validator.setDefaults({
        highlight: function (element) {
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
                    
            }
            else if(('input[type="checkbox"]').length > 0){
                console.log($(element).closest('.dimming').parent());
                $(element).closest('.dimming').parent().addClass("has-error");
            }
            else{
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
                if(('input[type="checkbox"]').length > 0){
                    console.log(element.closest('.dimming').parent());
                    error.insertAfter(element.closest().find('.dimming'));
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
    $("#frm_body_finish_color").validate({
        rules: {
            name: {
                required: true,
                maxlength:255      
            },
            code: {
                required: true,
            },
            sort_order:{
                required: true,
            }
        },
        messages: {
            name: {
                required: "Name is required.",
                maxlength:'Maximum 255 characters are allowed'
            },
            code: {
                required: "Code is required.",
            },
            sort_order:{
                required: 'Sort order is required',
            }
        }
    });
    $("#frm_group").validate({
        rules: {
            title: {
                required: true      
            },
        },
        messages: {
            title: {
                required: "title is required.",
            }
            
        }
    });
    $("#frm_attribute").validate({
        rules: {
            attribute_name: {
                required: true      
            },
            group_id: {
                required: true      
            },
            
        },
        messages: {
            attribute_name: {
                required: "Attribute name is required.",
            },
            group_id: {
                required: "Group is required.",
            },
            
        }
    });
    $("#frm_category").validate({
        rules: {
            title: {
                required: true      
            },
            slug: {
                required: true      
            },
            in_menu: {
                required: true      
            },
            
        },
        messages: {
            title: {
                required: "Title is required.",
            },
            slug: {
                required: "Slug is required.",
            },
            in_menu: {
                required: "In menu is required.",
            },
            
        }
    });
    $("#frm_project").validate({
        rules: {
            name: {
                required: true      
            },
            location: {
                required: true      
            },
            type: {
                required: true      
            },
         
        },
        messages: {
            name: {
                required: "Name is required.",
            },
            location: {
                required: "Location is required.",
            },
            type: {
                required: "Type is required.",
            },
          
        }
    });
    $("#frm_tag").validate({
        rules: {
            display_name: {
                required: true      
            },
            name: {
                required: true      
            },
            file:{
                required:true
            },
            slug:{
                required:true
            }
         
        },
        messages: {
            display_name: {
                required: "Display Name is required.",
            },
            name: {
                required: "Name is required.",
            },  
            file:{
                required: "Hero image is required."
            },
            slug: {
                required: "Slug is required.",
            },  
        }
    });
    $("#frm_tag_edit").validate({
        rules: {
            display_name: {
                required: true      
            },
            name: {
                required: true      
            },
            slug:{
                required:true
            }
        },
        messages: {
            display_name: {
                required: "Display Name is required.",
            },
            name: {
                required: "Name is required.",
            },
            slug: {
                required: "Slug is required.",
            },  
        }
    });
    $("#frm_sub_tag").validate({
        rules: {
            display_name: {
                required: true      
            },
            name: {
                required: true      
            },
            file:{
                required:true
            },
            banner:{
                required:true
            },
            tags: {
                required: true      
            },
            slug:{
                required:true
            }
         
        },
        messages: {
            display_name: {
                required: "Display Name is required.",
            },
            name: {
                required: "Name is required.",
            },  
            file:{
                required: "Hero image is required."
            },
            banner:{
                required: "Banner is required."
            },
            tags: {
                required: "Tag is required.",
            },
            slug: {
                required: "Slug is required.",
            },    
        }
    });
    $("#frm_sub_tag_edit").validate({
        rules: {
            display_name: {
                required: true      
            },
            name: {
                required: true      
            },
            tags: {
                required: true      
            },
            slug:{
                required:true
            }
        },
        messages: {
            display_name: {
                required: "Display Name is required.",
            },
            name: {
                required: "Name is required.",
            },  
            tags: {
                required: "Tag is required.",
            },  
            slug: {
                required: "Slug is required.",
            },  
           
        }
    });
    $("#frm_product").validate({
        rules: {
            title: {
                required: true      
            },
            category: {
                required: true      
            },
            featured_image: {
                required: true      
            },
            sub_tag_ids: {
                required: true      
            },
         
        },
        messages: {
            title: {
                required: "Title is required.",
            },
            category: {
                required: "Featured image is required",
            },
            sub_tag_ids: {
                required: 'Tag is required', 
            },
           
        }
    });
    $("#frm_product").validate({
        rules: {
            title: {
                required: true      
            },
            category: {
                required: true      
            },
            
            sub_tag_ids: {
                required: true      
            },
         
        },
        messages: {
            title: {
                required: "Title is required.",
            },
            category: {
                required: "Category is required",
            },
           
            sub_tag_ids: {
                required: 'Tag is required', 
            },
           
        }
    });
    $("#frm_job").validate({
        rules: {
            title: {
                required: true      
            },
            location: {
                required: true      
            },
            
            short_description: {
                required: true      
            },
            
            description: {
                required: true      
            },
         
        },
        messages: {
            title: {
                required: "Title is required.",
            },
            location: {
                required: "Location is required",
            },
           
            short_description: {
                required: 'Short Description is required', 
            },
           
            description: {
                required: 'Description is required', 
            },
           
        }
    });
    $("#frm_job_edit").validate({
        rules: {
            title: {
                required: true      
            },
            location: {
                required: true      
            },
            
            short_description: {
                required: true      
            },
            
            description: {
                required: true      
            },
         
        },
        messages: {
            title: {
                required: "Title is required.",
            },
            location: {
                required: "Location is required",
            },
           
            short_description: {
                required: 'Short Description is required', 
            },
           
            description: {
                required: 'Description is required', 
            },
           
        }
    });
    $("#frm_event").validate({
        rules: {
            name: {
                required: true      
            },
            slug: {
                required: true      
            },
            location: {
                required: true      
            },
         
        },
        messages: {
            name: {
                required: "Name is required.",
            },
            name: {
                required: "Slug is required.",
            },
            location: {
                required: "Location is required",
            },
           
        }
    });
});
