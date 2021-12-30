$(document).ready(function() {

  // Init modules
  ModuleMenu.init('/');
  ModuleFeed.load('#moduleFeed', true);

  // Event listeners
  $(document).on('modulePost.add:success', function(/*event, response*/) {
    ModuleFeed.load('#moduleFeed', true);
  });

});