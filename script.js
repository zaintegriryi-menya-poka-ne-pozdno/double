define(['jquery', 'underscore', 'twigjs'], function ($, _, Twig) {
  var CustomWidget = function () {
    var self = this;

    this.getTemplate = _.bind(function (template, params, callback) {
      params = (typeof params == 'object') ? params : {};
      template = template || '';

      return this.render({
        href: '/templates/' + template + '.twig',
        base_path: this.params.path,
        v: this.get_version(),
        load: callback
      }, params);
    }, this);

    this.callbacks = {
      render: function () {
        console.log('render');
        return true;
      },
      init: _.bind(function () {
        // /leads?page=2&limit=250
        let $allLeadsXolodka = [];
        let $allLeadsBD = [];
        let $count = 0;
        $.when($.get( "/api/v4/leads?page=1&limit=250&filter[pipeline_id]=4053727&filter[statuses]=38437138"),
            $.get( "/api/v4/leads?page=2&limit=250&filter[pipeline_id]=4053727&filter[statuses]=38437138"),
            $.get( "/api/v4/leads?page=3&limit=250&filter[pipeline_id]=4053727&filter[statuses]=38437138"),
            $.get( "/api/v4/leads?page=4&limit=250&filter[pipeline_id]=4053727&filter[statuses]=38437138"),
            $.get( "/api/v4/leads?page=5&limit=250&filter[pipeline_id]=4053727&filter[statuses]=38437138"),
            $.get( "/api/v4/leads?page=6&limit=250&filter[pipeline_id]=4053727&filter[statuses]=38437138"),
        ).done(function(data1,  data2, data3,data4,  data5, data6) {
          for($i=0; $i<data1[0]._embedded.leads.length; $i++ ) {
              $allLeadsXolodka.push( data1[0]._embedded.leads[$i]);
          }
          for($i=0; $i<data2[0]._embedded.leads.length; $i++ ) {
            $allLeadsXolodka.push( data2[0]._embedded.leads[$i]);
          }
          for($i=0; $i<data3[0]._embedded.leads.length; $i++ ) {
            $allLeadsXolodka.push( data3[0]._embedded.leads[$i]);
          }
          for($i=0; $i<data4[0]._embedded.leads.length; $i++ ) {
            $allLeadsXolodka.push( data4[0]._embedded.leads[$i]);
          }
          for($i=0; $i<data5[0]._embedded.leads.length; $i++ ) {
            $allLeadsXolodka.push( data5[0]._embedded.leads[$i]);
          }
          for($i=0; $i<data6[0]._embedded.leads.length; $i++ ) {
            $allLeadsXolodka.push( data6[0]._embedded.leads[$i]);
          }
          // $allLeadsXolodka.length
        });
        $.when($.get("/api/v4/leads?page=1&limit=250&filter[pipeline_id]=2279392"),
            $.get("/api/v4/leads?page=2&limit=250&filter[pipeline_id]=2279392"),
            $.get("/api/v4/leads?page=3&limit=250&filter[pipeline_id]=2279392"),
            $.get("/api/v4/leads?page=4&limit=250&filter[pipeline_id]=2279392"),
            $.get("/api/v4/leads?page=1&limit=250&filter[pipeline_id]=2279395"),
            $.get("/api/v4/leads?page=2&limit=250&filter[pipeline_id]=2279395"),
            $.get("/api/v4/leads?page=3&limit=250&filter[pipeline_id]=2279395"),
            $.get("/api/v4/leads?page=4&limit=250&filter[pipeline_id]=2279395"),
        ).done(function (data1bd, data2bd, data3bd, data4bd, data5bd, data6bd, data7bd, data8bd) {
          for ($i = 0; $i < data1bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data1bd[0]._embedded.leads[$i]);
          }
          for ($i = 0; $i < data2bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data2bd[0]._embedded.leads[$i]);
          }
          for ($i = 0; $i < data3bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data3bd[0]._embedded.leads[$i]);
          }
          for ($i = 0; $i < data4bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data4bd[0]._embedded.leads[$i]);
          }
          for ($i = 0; $i < data5bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data5bd[0]._embedded.leads[$i]);
          }
          for ($i = 0; $i < data6bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data6bd[0]._embedded.leads[$i]);
          }
          for ($i = 0; $i < data7bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data7bd[0]._embedded.leads[$i]);
          }
          for ($i = 0; $i < data8bd[0]._embedded.leads.length; $i++) {
            $allLeadsBD.push(data8bd[0]._embedded.leads[$i]);
          }
          checkNomerXolodka($allLeadsXolodka,$allLeadsBD);
        });

        function checkNomerXolodka($allLeadsXolodka,$allLeadsBD) {
          for ($i = 0; $i < $allLeadsXolodka.length; $i++) {
            if (($allLeadsXolodka[$i]._embedded.companies.length != "0") || ($allLeadsXolodka[$i]._embedded.companies.length != " ")) {
              $idcompaniesxolodka = $allLeadsXolodka[$i]._embedded.companies[0].id;
              $idLeadsXolodka = $allLeadsXolodka[$i].id;
              $.get("/api/v4/companies/" + $idcompaniesxolodka + "").done(function (data) {
                $kolnomercompanies = data.custom_fields_values[0].values.length;
                for ($j = 0; $j < $kolnomercompanies; $j++) {
                  $telcompaniesxolodka = ((data.custom_fields_values[0].values[$j].value).replace(/^[0-9]+\.[0-9]$/i)).substr(1);
                  console.log($telcompaniesxolodka);
                  console.log("Номер комп воронка Холодка");
                  checkNomerBD($telcompaniesxolodka, $idcompaniesxolodka,$allLeadsBD,$idLeadsXolodka);
                }
              });
            }
          }
        };
        function checkNomerBD($telcompaniesxolodka,$idcompaniesxolodka,$allLeadsBD,$idLeadsXolodka){
          for ($i = 0; $i < $allLeadsBD.length; $i++) {
            if (($allLeadsBD[$i]._embedded.companies.length != "0") || ($allLeadsBD[$i]._embedded.companies.length != " ")) {
              $idcompaniesBD = $allLeadsBD[$i]._embedded.companies[0].id;
              $idLeadsBD = $allLeadsBD[$i].id;
              $.get("/api/v4/companies/" + $idcompaniesBD + "").done(function (databd) {
                console.log(databd);
                $kolnomercompaniesBD = databd.custom_fields_values[0].values.length;
                console.log(databd.custom_fields_values[0].values.length);
                for ($j = 0; $j < $kolnomercompaniesBD; $j++) {
                  $telcompaniesBD = ((databd.custom_fields_values[0].values[$j].value).replace(/^[0-9]+\.[0-9]$/i)).substr(1);
                  console.log($telcompaniesBD);
                  console.log("Номер комп наш БД");
                  if ($telcompaniesBD == $telcompaniesxolodka) {
                    console.log("telcompaniesBD == telcompaniesxolodka");
                    AddLeads($idcompaniesxolodka,$idLeadsBD,$idLeadsXolodka);
                  } else {
                    console.log("FALSE");
                  }
                }
              });
            }
          }
        };
        function  AddLeads($idcompaniesxolodka,$idLeadsBD,$idLeadsXolodka) {
          let data = [
            {
              "to_entity_id": $idLeadsBD,
              "to_entity_type": "leads",
            }
          ]
          $.ajax({
            url: "/api/v4/companies/" + $idcompaniesxolodka + "/link",
            type: 'POST',
            data: JSON.stringify(data),
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            success: function(data){console.log(data);},
            failure: function(errMsg) {
              console.log(errMsg);
            }
          });
          changeEtLeads($idLeadsXolodka);
        };
        function changeEtLeads($idLeadsXolodka){
          let data = [
            {
              "id" : $idLeadsXolodka,
              "pipeline_id": 4053727,
              "status_id": 38899543,
            }
          ]
          $.ajax({
            url: "/api/v4/leads",
            type: 'PATCH',
            data: JSON.stringify(data),
            dataType: 'json',
            contentType: "application/json; charset=utf-8",
            success: function(data){console.log(data);},
            failure: function(errMsg) {
              console.log(errMsg);
            }
          });
        };
        console.log('init');
        AMOCRM.addNotificationCallback(self.get_settings().widget_code, function (data) {
          console.log(data)
        });

        this.add_action("phone", function (params) {
          /**
           * код взаимодействия с виджетом телефонии
           */
          console.log(params)
        });

        this.add_source("sms", function (params) {
          /**
           params - это объект в котором будут  необходимые параметры для отправки смс

           {
             "phone": 75555555555,   // телефон получателя
             "message": "sms text",  // сообщение для отправки
             "contact_id": 12345     // идентификатор контакта, к которому привязан номер телефона
          }
           */

          return new Promise(_.bind(function (resolve, reject) {
              // тут будет описываться логика для отправки смс
              self.crm_post(
                'https://example.com/',
                params,
                function (msg) {
                  console.log(msg);
                  resolve();
                },
                'text'
              );
            }, this)
          );
        });

        return true;
      }, this),
      bind_actions: function () {
        console.log('bind_actions');
        return true;
      },
      settings: function () {
        return true;
      },
      onSave: function () {
        alert('click');
        return true;
      },
      destroy: function () {

      },
      contacts: {
        //select contacts in list and clicked on widget name
        selected: function () {
          console.log('contacts');
        }
      },
      leads: {
        //select leads in list and clicked on widget name
        selected: function () {
          console.log('leads');
        }
      },
      tasks: {
        //select taks in list and clicked on widget name
        selected: function () {
          console.log('tasks');
        }
      },
      advancedSettings: _.bind(function () {
        var $work_area = $('#work-area-' + self.get_settings().widget_code),
          $save_button = $(
            Twig({ref: '/tmpl/controls/button.twig'}).render({
              text: 'Сохранить',
              class_name: 'button-input_blue button-input-disabled js-button-save-' + self.get_settings().widget_code,
              additional_data: ''
            })
          ),
          $cancel_button = $(
            Twig({ref: '/tmpl/controls/cancel_button.twig'}).render({
              text: 'Отмена',
              class_name: 'button-input-disabled js-button-cancel-' + self.get_settings().widget_code,
              additional_data: ''
            })
          );

        console.log('advancedSettings');

        $save_button.prop('disabled', true);
        $('.content__top__preset').css({float: 'left'});

        $('.list__body-right__top').css({display: 'block'})
          .append('<div class="list__body-right__top__buttons"></div>');
        $('.list__body-right__top__buttons').css({float: 'right'})
          .append($cancel_button)
          .append($save_button);

        self.getTemplate('advanced_settings', {}, function (template) {
          var $page = $(
            template.render({title: self.i18n('advanced').title, widget_code: self.get_settings().widget_code})
          );

          $work_area.append($page);
        });
      }, self),

      /**
       * Метод срабатывает, когда пользователь в конструкторе Salesbot размещает один из хендлеров виджета.
       * Мы должны вернуть JSON код salesbot'а
       *
       * @param handler_code - Код хендлера, который мы предоставляем. Описан в manifest.json, в примере равен handler_code
       * @param params - Передаются настройки виджета. Формат такой:
       * {
       *   button_title: "TEST",
       *   button_caption: "TEST",
       *   text: "{{lead.cf.10929}}",
       *   number: "{{lead.price}}",
       *   url: "{{contact.cf.10368}}"
       * }
       *
       * @return {{}}
       */
      onSalesbotDesignerSave: function (handler_code, params) {
        var salesbot_source = {
            question: [],
            require: []
          },
          button_caption = params.button_caption || "",
          button_title = params.button_title || "",
          text = params.text || "",
          number = params.number || 0,
          handler_template = {
            handler: "show",
            params: {
              type: "buttons",
              value: text + ' ' + number,
              buttons: [
                button_title + ' ' + button_caption,
              ]
            }
          };

        console.log(params);

        salesbot_source.question.push(handler_template);

        return JSON.stringify([salesbot_source]);
      },
    };
    return this;
  };

  return CustomWidget;
});