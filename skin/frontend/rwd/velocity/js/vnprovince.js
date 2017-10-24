
var VnProvince = Class.create();
VnProvince.prototype = {
    initialize: function (config, type, streetId,cityId, countryId) {
        this.config = config;
        this.type = type;
        this.streetId = streetId;
        this.cityId = cityId;
        this.countryId = countryId;
        this.rebuildElement();
        $(countryId).observe('change', this.rebuildElement.bind(this));
    },
    rebuildElement: function(){
        var countryValue = $(this.countryId).value;
        if(countryValue=='VN'){
            var provinceTemplate = new Template('<select id="' + this.type + '-vn-district" name="' + this.type + '[city]"><option value="">Select one District</option></select>');

            $(this.cityId).up('div').insert(provinceTemplate.evaluate());
            $(this.cityId).remove();
            var $this = this;
            $('' + this.type + '-vn-district').observe('change', function(e) {
                var districtSelect = document.getElementById('' + $this.type + '-vn-district');
                for(var i=0; i<districtSelect.options.length; i++){
                    if(districtSelect.options[i].value==districtSelect.value){
                        var districtId = districtSelect.options[i].getAttribute('districtId');
                        break;
                    }
                }
                var data = $this.config.ward[districtId];
                var ward = document.getElementById('' + $this.type + '-vn-street');
                $this.resetSelectOptions(ward);
                for( var j=0; j<data.length; j++){
                    var option = document.createElement("option");
                    option.text = data[j].label;
                    option.value = data[j].label;
                    ward.add(option)
                }
            });
            var streetTemplate = new Template('<select id="' + this.type + '-vn-street" name="' + this.type + '[street2]"><option value="">Select one Ward</option></select>');
            $(this.streetId).up('div').insert(streetTemplate.evaluate());
            $(this.streetId).remove();
        } else {
            if($(this.type + '-vn-district') != undefined){
                var provinceTemplate = new Template('<input type="text" id="' + this.type + ':city" name="' + this.type + '[city]"/>');

                $(this.type + '-vn-district').up('div').insert(provinceTemplate.evaluate());
                $(this.type + '-vn-district').remove();
            }
            if($(this.type + '-vn-street') != undefined){
                var provinceTemplate = new Template('<input type="text" id="' + this.type + ':street2" name="' + this.type + '[street2]"/>');

                $(this.type + '-vn-street').up('div').insert(provinceTemplate.evaluate());
                $(this.type + '-vn-street').remove();
            }
        }

        var stateValue = $(this.type + ':region_id').value;
        if(stateValue!=''){
            this.reBuildDistrict(stateValue);
        }

    },
    reBuildDistrict: function(stateValue) {
        var ward = document.getElementById('' + this.type + '-vn-street');
        this.resetSelectOptions(ward);
        var data = this.config.district[stateValue];
        var district = document.getElementById('' + this.type + '-vn-district');
        this.resetSelectOptions(district);
        for( var j=0; j<data.length; j++){
            var option = document.createElement("option");
            option.text = data[j].label;
            option.value = data[j].label;
            option.setAttribute('districtId',data[j].value);
            district.add(option)
        }
    },

    resetSelectOptions: function(selects){
        selects.value = '';
        for(var i=0; i<selects.options.length; i++){
            if(selects.options[i].value!=''){
                selects.remove(i);
                this.resetSelectOptions(selects);
            }
        }
    }
};