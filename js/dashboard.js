var containerHeading =   document.getElementById("container-heading");
var user_messages;
var habitus_challenges;
var habitusGroups;
var habitusMessages;
var habitus_js_challenge_list = document.querySelector('#js-challenges');
var habitusGroups_ul = document.querySelector('#js-groups');
var habitusMessages_ul = document.querySelector('#js-messages');

// populateChallengesUl();
// populateMessagesUl();
// populateGroupsUl();

document.querySelectorAll('.active-challenge-item').forEach(item => {
    item.addEventListener('click', event => {
        containerHeading.innerText = item.textContent;
    })
  })

  function populateChallengesUl() {
    habitus_challenges.forEach((challenge) => {
        let listItem = document.createElement("li");
        let link = document.createElement("a");
        link.setAttribute('href', challenge.link);
        link.innerHTML = challenge.title;
        listItem.appendChild(link);
        habitus_js_challenge_list.append(listItem);
    })
}

function populateMessagesUl() {
    habitusMessages.forEach((msg) => {
        let listItem = document.createElement("li");
        let link = document.createElement("a");
        link.setAttribute('href', msg.url);
        link.innerHTML = msg.subject;
        listItem.appendChild(link);
        habitusMessages_ul.append(listItem);
    })
}

function populateGroupsUl() {
    habitusGroups.forEach((group) => {
        let listItem = document.createElement("li");
        let link = document.createElement("a");
        link.setAttribute('href', group.url);
        link.innerHTML = group.name;
        listItem.appendChild(link);
        habitusGroups_ul.append(listItem);
    });
}

