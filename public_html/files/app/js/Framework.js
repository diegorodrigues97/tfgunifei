class Framework {

    constructor (data, $document) {

        this.data = data;
        this.$document = $document;
        this.verifyForeach();
        this.verifyConditional();
    }

    verifyForeach() {

        var htmlCode = this.$document.getElementsByTagName('foreach');

        if(htmlCode.length === 0) {
            // there is no foreach tag
            console.log('there is no foreach');
            return;
        }

        const listName = this.getDataValue(htmlCode);

        if(!listName) {
            // invalid tag data
            console.log('invalid tag data');
            return;
        }

        const elementName = this.getElementValue(htmlCode);

        if(!elementName) {
            // invalid tag element
            console.log('invalid tag element');
            return;
        }

        const property = this.checkElementTag();

        if(property === false) {
            // invalid element tag
            console.log('invalid element tag');
            return;
        }

        if(!(Object.keys(this.data)[0] === listName)) {
            // data != listName
            console.log('data != listName');
            return;
        }

        const ttype = (Object.keys(this.data[listName][0]).length)!=0?true:false

        const regex = /(<element[^>]+><\/element>)/g;

        let htmlAux = '';

        if((property.length === 0)) {
            for(let i = 0; i < Object.keys(this.data[listName]).length; i++) {
                htmlAux = htmlAux + htmlCode[0].innerHTML.replace(regex, this.data[listName][i]) + '\n'
            }
            console.log('case 1: has no key');
        }
        
        else if((property == Object.keys(this.data[listName][0])) && ttype) {
            for(let i = 0; i < Object.keys(this.data[listName]).length; i++) {
                htmlAux = htmlAux + htmlCode[0].innerHTML.replace(regex, this.data[listName][i][property]) + '\n'
            }
            console.log('case 2: has key');
        }

        else {
            // throw an exception
            console.log('something wrong');
            return;
        }
        
        htmlCode[0].innerHTML = htmlAux;

    }

    getDataValue(htmlCode) { // foreach tag

        if(htmlCode[0].attributes[0].name === 'data') {
            return htmlCode[0].attributes[0].value;
        }

        else {
            return false;
        }
    }

    getElementValue(htmlCode) { // foreach tag

        if(htmlCode[0].attributes[1].name === 'element') {
            return htmlCode[0].attributes[1].value;
        }

        else {
            return false;
        }
    }

    checkElementTag() { // element tag

        let htmlCode = this.$document.getElementsByTagName('element');

        if(htmlCode.length === 0) {
            // não existe tag element
            return false;
        }

        return this.getPropertyValue(htmlCode);

    }

    getPropertyValue(htmlCode) { // element tag

        if(htmlCode[0].attributes[0].name === 'property') {
            return htmlCode[0].attributes[0].value;
        }

        else {
            return false;
        }
    }

    verifyConditional() {

        // find if tag
        var ifCode = this.$document.getElementsByTagName('if');
        var ifElement = this.$document.querySelector('if');
        // check if it exists
        if(ifCode.length === 0) {
            // there is no if tag
            console.log('there is no if');
            return false;
        }
        let result = this.process(ifCode, this.data);
        //
        if(result) {
            if(ifElement.nextElementSibling.localName === 'elif') {
                ifElement.nextElementSibling.innerHTML = '';
            }
        
            if(ifElement.nextElementSibling.localName === 'else') {
                ifElement.nextElementSibling.innerHTML = '';
            }
    
            if(ifElement.nextElementSibling.nextElementSibling.localName === 'else') {
                ifElement.nextElementSibling.nextElementSibling.innerHTML = '';
            }
            console.log('if');
            
            return true;
        }
        //
        ifCode[0].innerHTML = ''
        
        var elifCode = this.$document.getElementsByTagName('elif');
        var elifElement = this.$document.querySelector('elif');
    
        result = this.process(elifCode, this.data);
    
        if(result) {
            if(elifElement.nextElementSibling.localName === 'else') {
                elifElement.nextElementSibling.innerHTML = '';
            }
            console.log('elif')
            return true;
        }
        //
        elifCode[0].innerHTML = '';
        console.log('else or none');
        return true;
    
    }
    
    process(htmlCode, data) {
        // get condition
        var condition = this.getCondition(htmlCode);
        // check if it is a valid condition
        if(!condition) {
            console.log('There is no condition!');
            // must be exception
            return false;
        }
        // process condition
        let result = this.processCondition(condition, data);
        //
        return result;
    
    }
    
    getCondition(htmlCode) { // 
        if(htmlCode[0].attributes[0].name === 'condition') {
            return htmlCode[0].attributes[0].value;
        }
    
        else {
            return false;
        }
    }
    
    processCondition(condition, data) {
    
        const keys = Object.keys(data);
        const values = Object.values(data);
    
        for(let i = 0; i < keys.length; i++) {
            var re = new RegExp(keys[i],"g");
            condition = condition.replace(re, values[i]);
        }
    
        try {
            return eval(condition);
        } catch(e) {
            throw alert(e);
        }
    }

}