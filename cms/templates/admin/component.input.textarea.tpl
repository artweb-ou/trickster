<div class="form_items{if $formErrors.$fieldName} form_error{/if}">
    <span class="form_label">
        {translations name="{$translationGroup}.{strtolower($fieldName)}"}
    </span>
    <div class="form_field">
        <div class="textarea_container">
            <textarea class="textarea_component{if !empty($item.textarea_class)} {$item.textarea_class}{/if}" name="{$formNames.$fieldName}">{$formData.$fieldName}</textarea>
        </div>
    </div>
</div>