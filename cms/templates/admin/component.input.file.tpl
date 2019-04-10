<div class="form_field_{$fieldName} {if $formErrors.$fieldName} form_error {/if}form_items">
    <span class="form_label">
        {translations name="{$translationGroup}.{strtolower($fieldName)}"}:
    </span>
    <div class="form_field">
        <input class="fileinput_placeholder" type="file" name="{$formNames.$fieldName}"/>
        {if $element->$fieldName}
            <div class="file_container">
                <span>{$formData.originalName2}</span>
                <a class="button file_delete_button warning_button" href="{$element->URL}id:{$element->id}/action:deleteFile/file:{$fieldName}">
                    <span class="icon icon_delete"></span>
                    {translations name="$fieldName.deletefile"}
                </a>
            </div>
        {/if}
    </div>
</div>