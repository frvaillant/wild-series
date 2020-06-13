   class Watchlistener {
        constructor(elem, action) {
            this.button = elem;
            this.classList = elem.classList;
            this.link = elem.dataset.target;
            this.action = action;
            this.button.addEventListener('click', (e) => {
                e.preventDefault()
                if (this.isInList()) {
                    this.removeFromList()
                } else {
                    this.addInList()
                }

                fetch(this.link).then(response => {
                    return response.text();
                }).then(json => {
                    this.action(json)
                });
            })
        }

       isInList() {
            if (this.classList.contains('in-list')) {
                return true
            }
            return false

        }

        addInList() {
            this.button.classList.add('in-list')
        }

        removeFromList() {
            this.button.classList.remove('in-list')
        }

   }

    document.addEventListener('DOMContentLoaded', () => {
        const watchListButtons = document.getElementsByClassName('watch-btn');
        for (let i = 0; i < watchListButtons.length; i++) {
            console.log(watchListButtons[i]);
            const watchListBtn = new Watchlistener(watchListButtons[i], (data) => {
                data = JSON.parse(data)
            })
        }
    })
