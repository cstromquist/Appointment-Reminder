$.jlm.component('NewCompany', 'companies.admin_index', function() {
    
    $('#sidebar .add').click(function() {
        // if ($('.new-dialog').size() > 0) {
        //     return false;
        // }
        // Hide sidebar contet
        var sidebarContent = $('#sidebar ul');
        sidebarContent.hide();
        
        var buttonEl = $(this);
        var formAction = buttonEl.attr('href');
        
        var templatePath = 'companies/new_company';;
        
        var dialogEl = $($.jlm.template(templatePath, { action: formAction }));
        
        var contentEl = $('#content_pad');
        
        contentEl.append(dialogEl);
        
        var toHeight = 230;
        
        var hiddenContentEls = contentEl.animate({
            height: toHeight
        }, 600, function() {
            // After the animation, focus the title input box
            $('.input:first input', dialogEl).focus();
        }).children().not(dialogEl).hide();
        
        // Bind cancel link
        $('.cancel-edit a', dialogEl).click(function() {
            dialogEl.remove();
            hiddenContentEls.show();
            contentEl.height('auto');
            sidebarContent.show();
            return false;
        });
        
        // Create link
        // TODO - first submit data by AJAX, on success redirect
        $('.submit input', dialogEl).click(function() {
            //$(this).attr('disabled', 'disabled').attr('value', '<l18n>Saving...</l18n>');
            return true;
        });
        
        return false;
    });
    
});

$.jlm.component('NewTechnician', 'companies.admin_index', function() {
    
    $('.company .add').click(function() {
        // if ($('.new-dialog').size() > 0) {
        //     return false;
        // }
        // Hide sidebar contet
        var sidebarContent = $('#sidebar ul');
        sidebarContent.hide();
        
        var buttonEl = $(this);
        var formAction = buttonEl.attr('href');
        
        var templatePath = 'technicians/new_technician';;
        
        var dialogEl = $($.jlm.template(templatePath, { action: formAction }));
        
        var contentEl = $('#content_pad');
        
        contentEl.append(dialogEl);
        
        var toHeight = 230;
        
        var hiddenContentEls = contentEl.animate({
            height: toHeight
        }, 600, function() {
            // After the animation, focus the title input box
            $('.input:first input', dialogEl).focus();
        }).children().not(dialogEl).hide();
        
        // Bind cancel link
        $('.cancel-edit a', dialogEl).click(function() {
            dialogEl.remove();
            hiddenContentEls.show();
            contentEl.height('auto');
            sidebarContent.show();
            return false;
        });
        
        // Create link
        // TODO - first submit data by AJAX, on success redirect
        $('.submit input', dialogEl).click(function() {
            //$(this).attr('disabled', 'disabled').attr('value', '<l18n>Saving...</l18n>');
            return true;
        });
        
        return false;
    });
    
});

$.jlm.component('NewTechnician', 'technicians.admin_index', function() {
    
    $('.add').click(function() {
        // if ($('.new-dialog').size() > 0) {
        //     return false;
        // }
        // Hide sidebar contet
        var sidebarContent = $('#sidebar ul');
        sidebarContent.hide();
        
        var buttonEl = $(this);
        var formAction = buttonEl.attr('href');
        
        var templatePath = 'technicians/new_technician';;
        
        var dialogEl = $($.jlm.template(templatePath, { action: formAction }));
        
        var contentEl = $('#content_pad');
        
        contentEl.append(dialogEl);
        
        var toHeight = 230;
        
        var hiddenContentEls = contentEl.animate({
            height: toHeight
        }, 600, function() {
            // After the animation, focus the title input box
            $('.input:first input', dialogEl).focus();
        }).children().not(dialogEl).hide();
        
        // Bind cancel link
        $('.cancel-edit a', dialogEl).click(function() {
            dialogEl.remove();
            hiddenContentEls.show();
            contentEl.height('auto');
            sidebarContent.show();
            return false;
        });
        
        // Create link
        // TODO - first submit data by AJAX, on success redirect
        $('.submit input', dialogEl).click(function() {
            //$(this).attr('disabled', 'disabled').attr('value', '<l18n>Saving...</l18n>');
            return true;
        });
        
        return false;
    });
    
});