var validator = {
    validate: function (input, constraints, name="This Field") {
        var constraint_list = constraints.split("|");

        if (!constraint_list.includes("required") && !input){
            return false;
        }

        var constraint = "";
        var response = "";
        for (var i = 0; i < constraint_list.length; i++){
            constraint = constraint_list[i];
            //Constraint has arguments i.e. min:8, max:10
            if (constraint.indexOf(":") > -1){
                var extra = constraint.split(":");
                if (this.hasOwnProperty(extra[0])) {
                    response = this[extra[0]](input, extra[1]);
                    if (response) {
                        return name + " " + response;
                    }
                }
            } else {
                if (this.hasOwnProperty(constraint)){
                    response = this[constraint](input);
                    if (response){
                        return name + " " + response;
                    }
                }

            }
        }
    },
    min: function (input, length) {
        if (input.length < length){
            return "must be at least " + length + " characters";
        }
    },
    max: function (input, length) {
        if (input.length > length){
            return "must be at most " + length + " characters";
        }
    },
    string: function (input) {
        if (typeof input != "string"){
            return "must be a string";
        }
    },
    numeric: function (input) {
        var num_rex = /[0-9]/;
        if (!input.match(num_rex)){
            return "must be a number";
        }
    },
    email: function (input) {

    },
    required: function(input){
        if (!input){
            return "is required"
        }
    }
};

export { validator as validator };