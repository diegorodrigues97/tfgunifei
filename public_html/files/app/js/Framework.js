class Framework {

    constructor (data, $document) {

        this.data = data;
        this.$document = $document;
        this.verifyForeach();
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

}