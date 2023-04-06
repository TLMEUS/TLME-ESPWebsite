function updateCategoryForm(id, cat) {
    var updateId = document.getElementById("updateId");
    var updateCategory = document.getElementById("updateCategory");
    updateId.value = id;
    updateCategory.value = cat;
    return false;
}

function updatePlanForm(id, category) {
    document.getElementById('category').innerHTML = category;
    var changeElement = document.getElementById("parent");
    changeElement.value = id;
}

function changePlanForm(id, caption, plan, field, value) {
    document.getElementById('plan').innerHTML = plan;
    document.getElementById('caption').innerHTML = caption;

    var changeId = document.getElementById('id');
    var changeField = document.getElementById('field');
    var changeValue = document.getElementById('newValue');
    changeId.value = id;
    changeField.value = field;
    changeValue.value = value;
}

function toggleForm(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}