import Cookies from 'js-cookie';

const getCookieName = id => `dialog_api_seen_${id}`;

const notSeenYet = element => !Cookies.get(getCookieName(element.id));

const markAsSeen = element => () => {
  Cookies.set(getCookieName(element.id), 1);
};

const show = element => {
  element.open = true;
  element.addEventListener('close', markAsSeen(element));
};

Drupal.behaviors.dialog_api = {
  attach: (context) => {
    Object.values(context.querySelectorAll('.dialog-api--dialog'))
      .filter(notSeenYet)
      .forEach(show);
  }
};
