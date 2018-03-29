import $ from 'jquery';

class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.openButton = document.querySelector('.js-search-trigger');
    this.closeButton = document.querySelector('.search-overlay__close');
    this.searchOverlay = document.querySelector('.search-overlay');
    this.searchField = document.querySelector('#search-term');
    this.resultsDiv = document.querySelector('#search-overlay__results');
    this.events();
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }

  // 2. events
  events() {
    this.openButton.addEventListener('click', this.openOverlay.bind(this));
    this.closeButton.addEventListener('click', this.closeOverlay.bind(this));
    document.addEventListener('keydown', this.keyPressDispatcher.bind(this));
    this.searchField.addEventListener('keyup', this.typingLogic.bind(this));
  }

  // 3. methods (function, action...)
  typingLogic() {
    if (this.searchField.value !== this.previousValue) {
      clearTimeout(this.typingTimer);

      if (this.searchField.value) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>';
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
      } else {
        this.resultsDiv.innerHTML = '';
        this.isSpinnerVisible = false;
      }

      this.previousValue = this.searchField.value;
    }
  }

  getResults() {
    fetch(`http://fictional-university.local/wp-json/wp/v2/posts?search=${this.searchField.value}`)
      .then((response) => {
        if (response.ok) {
          return response.json()
        }
        throw new Error('Network response was not ok.');
      })
      .then((data) => {
        this.resultsDiv.innerHTML = `
          <h2 class='search-overlay__section-title'>General Information</h2>
          <ul class='link-list min-list'>
            ${data.map(item => `<li><a href='${item.link}'>${item.title.rendered}</li>`).join('')}
          </ul>
        `})
      .catch((err) => {
        console.log(`Fetch Error: ${err}`);
      });
  }

  openOverlay() {
    this.searchOverlay.classList.add('search-overlay--active');
    document.body.classList.add('body-no-scroll');
    setTimeout(() => this.searchField.focus(), 500);
    this.isOverlayOpen = true;
  }

  closeOverlay() {
    this.searchOverlay.classList.remove('search-overlay--active');
    document.body.classList.remove('body-no-scroll');
    this.isOverlayOpen = false;
  }

  keyPressDispatcher(e) {

    if (e.keyCode === 83 && !this.isOverlayOpen && document.querySelectorAll('input:focus, textarea:focus').length === 0) {
      this.openOverlay();
    }

    if (e.keyCode === 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }
}

export default Search;