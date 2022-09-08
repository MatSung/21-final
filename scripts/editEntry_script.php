<script>
    //console.log(document.querySelector("imones_tipas" + "-table"));
    function editEntry(tableName, entryButton) {
        let table = document.querySelector("#"+tableName + "-table");
        let tabletr = document.querySelector("#"+tableName + "-table").tHead.childNodes[0];
        //console.log(tableName + "-table");
        let row = entryButton.parentElement.parentElement;
        //console.log(row);
        
        //edited item ID
        let itemID = row.childNodes[0].innerHTML;

        //row index
        let rowIndexIndex = row.rowIndex;
        //console.log(rowIndex);
        let headers = [];

        //change old button to a submit button so it stops making stuff
        //let editButton = document.querySelector("#imones_tipas"+entryID);
        entryButton.setAttribute("disabled","");
        //console.log(tabletr);
        //get header names minus id and minus action
        for(let i = 1; i < tabletr.childNodes.length - 1; i++){
            headers.push(tabletr.childNodes[i].innerHTML);
        }
        //console.log(headers);
        // i have a row index where it needs to be put
        // i have the names of the indexes
        // i must make a new row

        var newRow = table.insertRow(rowIndexIndex+1);
        let cancelButton = document.createElement("button");
        cancelButton.appendChild(document.createTextNode("X"));
        cancelButton.setAttribute("onclick","cancelme(this);");
        cancelButton.setAttribute("class","btn btn-danger");

        let rowValues = [cancelButton];
        //console.log(headers);
        let inputFields = [];
        for(let i = 0; i < headers.length;i++){
            let newInput = document.createElement("input");
            newInput.setAttribute("name",headers[i]);
            newInput.setAttribute("type","text");
            newInput.setAttribute("class","form-control-sm form-control");
            newInput.setAttribute("required","");
            newInput.setAttribute("placeholder",headers[i]);
            newInput.setAttribute("id", headers[i]);
            //newInput.setAttribute("onchange","updateMyself(this);");
            inputFields.push(newInput);
        }
        //rowValues = rowValues.concat(headers);


        let confirmEditButton = document.createElement("button");
        confirmEditButton.appendChild(document.createTextNode("GO"));
        confirmEditButton.setAttribute("name",tableName+"UpdateEntry");
        confirmEditButton.setAttribute("onclick","confirmEdit(this);");
        confirmEditButton.setAttribute("type","button");
        confirmEditButton.setAttribute("class","btn btn-info");

        rowValues.push("Actions");
        //console.log(rowValues);
        let rowData = [];
        let rowForm = document.createElement("form");
        rowForm.setAttribute("name",tableName+"UpdateEntry");
        rowForm.setAttribute("method","POST");
        
        let hiddenInput = document.createElement("input");
        hiddenInput.setAttribute("hidden","");
        hiddenInput.setAttribute("value",itemID);
        hiddenInput.setAttribute("name","id");

        //newRow.appendChild(rowForm);
        //put each of them in a td
        let tdList = [];
        tdList.push(document.createElement("td"));
        tdList[0].appendChild(cancelButton);
        //rowData.push(cancelButton);
        rowForm.appendChild(hiddenInput);
        for(var i = 0; i < inputFields.length; i++){
            //make fake inputs
            tdList.push(document.createElement("td"));
            tdList[i+1].appendChild(inputFields[i]);
            
            //make the hidden inputs
            let hiddenHeaderInput = document.createElement("input");
            hiddenHeaderInput.setAttribute("hidden","");
            hiddenHeaderInput.setAttribute("name",headers[i]);
            rowForm.appendChild(hiddenHeaderInput);
            //rowData.push(inputFields[i]);
        }
        let submitTypeInput = document.createElement("input");
        submitTypeInput.setAttribute("name",tableName+"UpdateEntry");
        submitTypeInput.setAttribute("hidden","");
        rowForm.appendChild(submitTypeInput);

        let finaltd = document.createElement("td");


        //hidden edits


        rowForm.appendChild(confirmEditButton);
        finaltd.appendChild(rowForm);
        tdList.push(finaltd);

        //rowData.push(confirmEditButton);
        //rowData.forEach(element => );
        
        //cant put a form inside of a table, must be in a cell or the whole table.
        //javascript it all into the button in the single cell and then submit through javascript
        //put all the inputs into the last place and mirror them into the cells



        tdList.forEach(element => newRow.appendChild(element));
        //console.log(rowValues);

        



        //insert a new row under that containing
        //id frozen, the rest of the keys.
        

    }

    function cancelme(item){
        let table = item.parentElement.parentElement.parentElement.parentElement;
        let rowIndexIndex = item.parentElement.parentElement.rowIndex;
        //console.log(rowIndexIndex);

        //find the button and reenable it


        table.deleteRow(rowIndexIndex);
    }

    function confirmEdit(theButton){
        
        //get all of the info from the inputs and put them into my cell
        let tr = theButton.parentElement.parentElement.parentElement;
        //console.log(tr);
        

        let form = theButton.parentElement;
        for(let i = 1; i<form.childNodes.length-1; i++){
            let theNode = tr.childNodes[i].childNodes[0].value;
            if(theNode == ''){
                alert("insert something pease");
                return 0;
            }
            form.childNodes[i].value = theNode;
            //console.log(theNode);
        }
        
        

        theButton.parentElement.submit();

    }

    //function updateMyself(myself){
    //    myself.value
    //}
    
</script>