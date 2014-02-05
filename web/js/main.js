URL = 'http://aprendesilex.no-ip.info/web/index_dev.php';

var AppRouter = Backbone.Router.extend({
    routes: {
        "beers": "getBeers",
        "beers/:code": "getBeer",
        "home": "homePage",
        "/": "homePage"
    }
});

/**
 * Beers Model
 * @type {Backbone.Model}
 */
var Beers = Backbone.Model;
var BeersCollection = Backbone.Collection.extend({
    initialize: function(model,options){
        if (options.code)
            this.code = "/" + options.code
        else 
            this.code = ""
    },
    model: Beers,
    url: function() {
        return URL + '/api/beers' + this.code
    },
    parse: function (response) {
        return response
    }
});
var BeersView = Backbone.View.extend ({
    //el: '.page',
    render: function (code) {
        var that = this;
        var options;
        var _template;

        if (code){
            options = { 'code':code }
            _template = $('#beer-template').html();
            this.$el = $('.beer');
        }
        else{
            options = {}
            _template = $('#beers-template').html();
            this.$el = $('.beers');
        }

        var beer = new BeersCollection([],options);
        beer.fetch({
            success: function (BeersCollection) {
                var template = _.template(_template, {
                    BeersCollection: BeersCollection.models
                });
                that.$el.html(template);
            }
        });
    }
});

var beersView = new BeersView();
var app_router = new AppRouter;

app_router.on('route:getBeers',function(){
    beersView.render();
});

app_router.on('route:getBeer',function(code){
    beersView.render(code);
});

app_router.on('route:homePage',function(){
    $.ajax({
        url: URL + '/api/pages/home',
        dataType: "json",
        success: function(response){
            $('.beers').html(response.data);
            $('.beer').html('');
        }
    });
});

Backbone.history.start();
