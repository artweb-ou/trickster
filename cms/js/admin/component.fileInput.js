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
		fakeField.className = 'input_component file_input_field';
		fakeField.tabIndex = 0;
		componentElement.appendChild(fakeField);

		fakeButton = document.createElement('div');
		fakeButton.className = 'button file_input_button';
		componentElement.appendChild(fakeButton);

		var fakeButtonText = document.createElement('div');
		fakeButtonText.className = 'button_text';
		fakeButton.appendChild(fakeButtonText);

		var content = document.createTextNode(buttonText);
		fakeButtonText.appendChild(content);

		inputElement.parentNode.insertBefore(componentElement, inputElement);
		eventsManager.addHandler(componentElement, 'click', clickHandler);
	};
	var processInputElement = function() {
		inputElement.style.position = 'absolute';
		inputElement.style.visibility = 'hidden';
		eventsManager.addHandler(inputElement, 'change', synchronizeContent);

		synchronizeContent();
	};
	var synchronizeContent = function() {
		var content = document.createTextNode(inputElement.value);
		while (fakeField.firstChild) {
			fakeField.removeChild(fakeField.firstChild);
		}
		fakeField.appendChild(content);
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
