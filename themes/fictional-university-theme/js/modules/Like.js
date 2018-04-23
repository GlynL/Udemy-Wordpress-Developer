import $ from 'jquery';

class Like {
  constructor() {
    this.events();
  }

  events() {
    $('.like-box').on('click', this.ourClickDispatcher.bind(this));
  }

  // METHODS
  ourClickDispatcher(e) {
    // incase they click on the heart <i>
    var currentLikeBox = $(e.target).closest('.like-box');

    if (currentLikeBox.attr('data-exists') === 'yes') {
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }

  createLike(currentLikeBox) {
    $.ajax({
      // NONCE - otherwrise is_user_logged_in() will always evaluate false
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/university/v1/manageLike',
      type: 'POST',
      data: { 'professorId': currentLikeBox.data('professor') },
      success: (res) => {
        // update like box w/out refreseh
        currentLikeBox.attr('data-exists', 'yes');
        let likeCount = parseInt(currentLikeBox.find('.like-count').html(), 10);
        likeCount++;
        currentLikeBox.find('.like-count').html(likeCount);
        currentLikeBox.attr('data-like', res);
        console.log(res);
      },
      error: (err) => {
        console.log(err);
      }
    });
  }

  deleteLike(currentLikeBox) {
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/university/v1/manageLike',
      data: { 'like': currentLikeBox.attr('data-like') },
      type: 'DELETE',
      success: (res) => {
        // update like box w/out refreseh
        currentLikeBox.attr('data-exists', 'no');
        let likeCount = parseInt(currentLikeBox.find('.like-count').html(), 10);
        likeCount--;
        currentLikeBox.find('.like-count').html(likeCount);
        currentLikeBox.attr('data-like', '');
        console.log(res);
      },
      error: (err) => {
        console.log(err);
      }
    });
  }
}

export default Like;