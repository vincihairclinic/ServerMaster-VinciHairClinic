@push('js')
    <script>
        let modal = {
            action: function (title, message, type = 'success', id='ds-modal-action', parent='body') {
                let container = document.createElement('div');
                container.setAttribute('id', id);
                container.setAttribute('class', 'ds-modal-backdrop');
                let modal =  document.createElement('div');
                modal.setAttribute('class', 'ds-modal');
                let image =  document.createElement('div');
                image.setAttribute('class', 'ds-modal-image ' + type);
                let header =  document.createElement('div');
                header.setAttribute('class', 'ds-modal-header');
                let content =  document.createElement('div');
                content.setAttribute('class', 'ds-modal-content');
                let btnContainer =  document.createElement('div');
                btnContainer.setAttribute('class', 'ds-btn-container');
                let disagree =  document.createElement('button');
                disagree.setAttribute('class', 'disagree');
                let agree =  document.createElement('button');
                agree.setAttribute('class', 'agree');


                disagree.append('Cancel');
                agree.append('Confirm');
                header.append(title);
                content.append(message);
                btnContainer.append(disagree);
                btnContainer.append(agree);
                modal.append(image);
                modal.append(header);
                modal.append(content);
                modal.append(btnContainer);
                container.append(modal);
                $(parent).append(container);
                document.querySelector('body').style.overflow = 'hidden';
            },
            warning: function (message, title, type = 'error', id='ds-modal-action', parent='body') {
                let container = document.createElement('div');
                container.setAttribute('id', id);
                container.setAttribute('class', 'ds-modal-backdrop');
                let modal =  document.createElement('div');
                modal.setAttribute('class', 'ds-modal');
                let image =  document.createElement('div');
                image.setAttribute('class', 'ds-modal-image ' + type);
                let header =  document.createElement('div');
                header.setAttribute('class', 'ds-modal-header');
                let content =  document.createElement('div');
                content.setAttribute('class', 'ds-modal-content');
                let btnContainer =  document.createElement('div');
                btnContainer.setAttribute('class', 'ds-btn-container');
                let agree =  document.createElement('button');
                agree.setAttribute('class', 'agree');
                agree.addEventListener('click', function () {
                    container.remove();
                });
                agree.append('Confirm');
                header.append(message);
                content.append(title);
                btnContainer.append(agree);
                modal.append(image);
                modal.append(header);
                modal.append(content);
                modal.append(btnContainer);
                container.append(modal);
                $(parent).append(container);
                document.querySelector('body').style.overflow = 'hidden';
            },
            agree: function (action = null, id='#ds-modal-action button.agree', type = 'onclick') {
                if (!empty(action)) {
                    if (type === 'onclick') {
                        $(id).attr('onclick', action);
                    } else if (type === 'event') {
                        $(id).on('click', action);
                    }
                }
                $(id).on('click', function () {
                    $(id).closest('.ds-modal-backdrop')?.remove();
                    document.querySelector('body').style.overflow = '';
                });
            },
            disagree: function (action = null, id='#ds-modal-action button.disagree', type = 'onclick') {
                if (!empty(action)) {
                    if (type === 'onclick') {
                        $(id).attr('onclick', action);
                    } else if (type === 'event') {
                        $(id).on('click', action);
                    }
                }
                $(id).on('click', function () {
                    $(id).closest('.ds-modal-backdrop')?.remove();
                    document.querySelector('body').style.overflow = '';
                });
            },
        }
    </script>
@endpush