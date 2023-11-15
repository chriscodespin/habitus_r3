<?php
// if (!is_user_logged_in()) {
//     wp_redirect(esc_url(site_url('/')));
//     exit;
// }
get_header();

?>

<div class="container mt-5 p-5 bg-light rounded-3 border border-warning border-3">
    <div class="row row-cols-3 ">
        <div class="col border border-primary">
            <button id="ajax-button" type="button">Update content with Ajax</button>
                <h1 class="bg-warning">Active Challenges</h1>
                <div class="list-group" id="active-challenges"> </div>
        </div>
        <div class="col border border-primary">
            <button id="messages-button" type="button">Update content with Ajax</button>
                <h1 class="bg-warning">Messages</h1>
                <div class="list-group" id="messages"> </div>
        </div>
    </div>    
</div>

      <script>
        function getChallenges() {
          var target = document.getElementById("active-challenges");
          var uri = '<?php echo site_url('wp-json/habitus/v1/active-challenges?user_id=' . + get_current_user_id() ); ?>';

          var xhr = new XMLHttpRequest();
          xhr.open('GET', uri, true);
          xhr.onreadystatechange = function () {
            //console.log('readyState: ' + xhr.readyState);
            if(xhr.readyState == 2) {
              target.innerHTML = '<li>Loading...</li>';
            }
            if(xhr.readyState == 4 && xhr.status == 200) {
              var items = JSON.parse(xhr.responseText);
              console.log(items);
              var $output = `${items.map(item => `
                
                    <a class="list-group-item list-group-item-action list-group-item-success" href="${item.link}" >
                        ${item.title}
                    </a>                    
                `).join("")}`;

              target.innerHTML = $output;

            }
          }
          xhr.send();
        }

        function getMessages() {
          var target = document.getElementById("active-challenges");
          var uri = '<?php echo site_url('wp-json/habitus/v1/active-challenges?user_id=' . + get_current_user_id() ); ?>';

          var xhr = new XMLHttpRequest();
          xhr.open('GET', uri, true);
          xhr.onreadystatechange = function () {
            //console.log('readyState: ' + xhr.readyState);
            if(xhr.readyState == 2) {
              target.innerHTML = '<li>Loading...</li>';
            }
            if(xhr.readyState == 4 && xhr.status == 200) {
              var items = JSON.parse(xhr.responseText);
              console.log(items);
              var $output = `${items.map(item => `
                
                    <a class="list-group-item list-group-item-action list-group-item-success" href="${item.link}" >
                        ${item.title}
                    </a>                    
                `).join("")}`;

              target.innerHTML = $output;

            }
          }
          xhr.send();
        }
  
        var button = document.getElementById ("ajax-button");
        button.addEventListener("click", getChallenges);
      </script>