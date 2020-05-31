class FormSearch {

    constructor () {
        this.formZone        = null;
        this.progSelector    = null;
        this.urlProgram      = '/search/addseason';
        this.seasonSelector  = null;
        this.urlSeason       = '/search/addepisodes';
        this.program         = 0;
        this.season          = 0;
        this.seasonSelector  = null;
        this.urlResults      = '/search/results';
        this.episodeSelector = null;
        this.init();
    }

    init(program = 0, season = 0) {
        this.formZone        = document.querySelector('#form-search-zone');
        this.progSelector    = document.querySelector('#selector_Program');
        this.program         = program;
        this.seasonSelector  = document.querySelector('#selector_Season');
        this.season          = season;
        this.episodeSelector = document.querySelector('#selector_Episode');
        this.resultsZone     = document.getElementById('results');

        this.progSelector.addEventListener('change', (e) => {
            this.resultsZone.innerHTML = '';
            this.getSelector(this.urlProgram, e);
        })

        if (this.seasonSelector) {
            this.seasonSelector.addEventListener('change', (e) => {
                this.resultsZone.innerHTML = '';
                this.getSelector(this.urlSeason, e);
            })
        }

        if (this.episodeSelector) {
            this.episodeSelector.addEventListener('change', (e) => {
                this.resultsZone.innerHTML = '';
                this.showEpisode(this.urlResults, e);
            })
        }
    }

    getSelector(url)
        {
            const thisClass = this;
            const programId = this.progSelector.value;
            let seasonId    = (this.seasonSelector) ? this.seasonSelector.value : null;

            if (programId  != thisClass.program) {
                url         = this.urlProgram;
                seasonId    = null;
            }
            const data = {
                'Program': programId,
                'Season' : seasonId
            };
            this.ajaxPoster(url, data, (result)=> {
                thisClass.formZone.innerHTML = result.body.innerHTML;
                thisClass.init(programId, seasonId);
                thisClass.selectOption(programId, thisClass.progSelector);
                thisClass.selectOption(seasonId, thisClass.seasonSelector);
            })
        }

    showEpisode(url)
    {
        const thisClass  = this;
        const episode    = this.episodeSelector.value;
        const link       = url + '/' + episode;
        if (episode) {
            this.ajaxPoster(link, null, (result)=> {
                thisClass.resultsZone.innerHTML = result.body.innerHTML;
            })
        }
    }

    ajaxPoster(url, data, action) {
        const thisClass = this;
        fetch(url, {
            method      : 'POST',
            mode        : "same-origin",
            credentials : "same-origin",
            body        : JSON.stringify(data),
            headers     : {
                'Content-Type': 'application/json'
            },
        }).then(function (response) {
            return response.text();
        }).then(function (html) {
            const result = thisClass.parseHtml(html);
            action(result);
        });
    }

    selectOption(val, selector) {
        const option = selector.querySelector('option[value="' + val + '"]');
        if (option) {
            selector.options[option.index].selected = true;
        }
    }

    parseHtml (html) {
        const parser = new DOMParser();
        const result = parser.parseFromString(html, "text/html");
        return result;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const search = new FormSearch();
});

