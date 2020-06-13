   class Watchlistener {
        constructor(elem) {
            this.button = elem;
            this.classList = elem.classList;
            this.link = elem.dataset.target;

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
                    console.log(json);
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
            const watchListBtn = new Watchlistener(watchListButtons[i])
        }
    })
