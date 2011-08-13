$.jlm.component('CompanyChangeLogo', 'companies.admin_edit', function() {
    
    $('.company_logo a').click(function() {
        
        var buttonEl = $(this);
        
        buttonEl.hide();
        
        var formAction = buttonEl.attr('href');
        
        var templatePath = 'companies/change_logo';
        
        var dialogEl = $($.jlm.template(templatePath, { action: formAction }));
        
        var contentEl = $('.company_info');
        
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
            buttonEl.show();
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

$.jlm.component('CompanyChangePhoto', 'companies.admin_edit', function() {
    
    $('.company_photo a').click(function() {
        
        var buttonEl = $(this);
        
        buttonEl.hide();
        
        var formAction = buttonEl.attr('href');
        
        var templatePath = 'companies/change_photo';
        
        var dialogEl = $($.jlm.template(templatePath, { action: formAction }));
        
        var contentEl = $('.company_info');
        
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
            buttonEl.show();
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