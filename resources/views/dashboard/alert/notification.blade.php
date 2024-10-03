@once
    @push('js')
        <script>
            let notification = {
                top: function (message, type = 'success', parent = 'section.dashboard-content-container > .dashboard-content') {
                    let notification = document.createElement('div');
                    notification.classList.add('ds-notification');
                    notification.classList.add(type);
                    notification.append(message);
                    $(parent).prepend(notification);
                    setTimeout(function () {
                        notification.classList.add('notification-close');
                        setTimeout(function () {
                            notification.remove();
                        }, 500);
                    }, 7000);
                }
            }
        </script>
    @endpush
@endonce