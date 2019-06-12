function FileInputComponent(inputElement) {
    var componentElement;
    var fakeField;
    var fakeButton;
    var buttonText = '';

    var init = function() {

        if (window.translationsLogics.get('button.file_upload')) {
            buttonText = window.translationsLogics.get('button.file_upload');
        }

        createDom();
        processInputElement();
    };
    var createDom = function() {
        componentElement = document.createElement('div');
        componentElement.className = 'file_input_container';

        fakeField = document.createElement('div');
        fakeField.className = 'input_component file_input_field'; // file name etc...
        fakeField.tabIndex = 0;

        fakeButton = document.createElement('div');
        fakeButton.className = 'button file_input_button';
        componentElement.appendChild(fakeButton);

        var fakeButtonText = document.createElement('div');
        fakeButtonText.className = 'button_text';
        fakeButton.appendChild(fakeButtonText);

        var content = document.createTextNode(buttonText);
        fakeButtonText.appendChild(content);

        var inputParent = (inputElement.dataset.parent)?inputElement.form.querySelector(inputElement.dataset.parent):inputElement.parentNode;

        componentElement.appendChild(inputElement);

        var submit = (inputElement.dataset.firstChild)?inputParent.querySelector(inputElement.dataset.firstChild):inputParent.firstChild;
        inputParent.insertBefore(fakeField, submit);
        inputParent.insertBefore(componentElement, submit);

        eventsManager.addHandler(componentElement, 'click', clickHandler);
    };
    var processInputElement = function() {
        inputElement.style.position = 'absolute';
        inputElement.style.visibility = 'hidden';
        inputElement.style.left = 0;
        inputElement.style.left = '-1000px';
        // inputElement.style.top = 0;

        eventsManager.addHandler(inputElement, 'change', synchronizeContent);

        synchronizeContent();
    };
    var synchronizeContent = function() {
        let manusSuurus = '';
        let manusNimi = '';
        let manus = '';
        if (inputElement.value != '') {
            manusSuurus = (this.files[0].size / 1024 / 1024).toFixed(4);// + " MB"
            manusNimi = this.files[0].name;
            manus = manusNimi + ' (' + manusSuurus + '  MB)';
        }
        fakeField.innerHTML = manus;
    };
    var clickHandler = function() {
        if (typeof inputElement.click !== 'undefined') {
            inputElement.click();

        } else {
            eventsManager.fireEvent(inputElement, 'click');
        }
    };

    init();
}
