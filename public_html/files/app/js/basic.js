
$(document).on('click', '.requestRoute', function()
{
    var input = new Object();
    $.each($(this).data(), function(key, value)
    {
      input[key] = value;
    });

    var resp = Application.requestRoute(input);
    resp.done(function(e){
      r = JSON.parse(e);

      if(r['error'])
      {
        return alert(r['message']);
      }

      return window.location.href = r['route'];
    });
});

$(document).on('click', '.requestController', function()
{
    var input = new Object();
    $.each($(this).data(), function(key, value)
    {
      input[key] = value;
    });

    if(input['token'] === 'undefined')
    {
      return alert("Paran TOKEN not found!");
    }
    if(input['controller'] === 'undefined')
    {
      return alert('Param CONTROLLER not found!');
    }

    var resp = Application.requestController(input);
    resp.done(function(e){
      r = JSON.parse(e);

      if(r['error'])
      {
        return alert(r['message']);
      }

      if(!r['route'] && r['reload'])
      {
        return window.location.reload();
      }

      if(r['route'] != false)
      {
        return window.location.href = r['route'];
      }
    });
});
