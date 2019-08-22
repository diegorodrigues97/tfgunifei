class Application
{

  constructor(){}

  static requestController(input, options)
  {
     let config = { type: "POST",
                    dataType: "text",
                    url: '/request.php',
                    cache: false,
                    data: input
                  };
    if(typeof options !== 'undefined')
    {
      config = Object.assign(config,options);
    }

     return $.ajax(config);
   }

  static requestRoute(input)
  {
      input['#route#'] = true;

      if(typeof input['route'] === undefined)
          return alert('Param \'route\' not informed!');

      return $.ajax({
        type: "POST",
        dataType: "text",
        url: '/request.php',
        cache: false,
        data: input
      });
  }







}
