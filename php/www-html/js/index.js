let searchResult = [];

async function handleSelectRegion() {
    const val = document.querySelector('#region').value;
    const formData = new FormData();
    formData.append('action', 'regionSelected');
    formData.append('code', val);
    const response = await requestServer(formData);
    setCities(response);
    document.querySelector('#street').value = '';
    document.querySelector('#house').value = '';
}

async function handleSearch() {
    console.log('Search pressed')
    const region = document.querySelector('#region').value;
    const cityCode = document.querySelector('#city').value;
    const street = document.querySelector('#street').value;
    const house = document.querySelector('#house').value;
    const formData = new FormData();
    formData.append('action', 'search');
    formData.append('region', region);
    formData.append('code', cityCode);
    formData.append('street', street);
    formData.append('house', house);
    const response = await requestServer(formData);
    renderTable(response);
    const res = document.querySelector('#resultCount');
    res.innerHTML = response.length;
    searchResult = [...response];
}

async function saveRow() {
    if (searchResult.length === 0) {
        return;
    }
    const rows = searchResult.map((item) => (
        `${item.region}, ${item.city}, ${item.street}, ${item.house}`
    ));

    const formData = new FormData();
    formData.append('action', 'saveRow');
    formData.append('rows', JSON.stringify(rows));

    const res = await requestServer(formData);
    updateStatus(res.status);
}

async function saveFields() {
    if (searchResult.length === 0) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'saveFields');
    formData.append('rows', JSON.stringify(searchResult));

    const res = await  requestServer(formData);
    updateStatus(res.status);
}

function renderTable(data) {
    const tBody = document.querySelector('tbody');
    tBody.innerHTML = '';
    data.map((item, idx) => {
        console.log('data: ', item)
        const tr = document.createElement('tr', )
        tr.innerHTML= `<th scope="row">${idx+1}</th>`;
        tr.innerHTML= tr.innerHTML +=`<td>${item.region}</td>`;
        tr.innerHTML= tr.innerHTML +=`<td>${item.city}</td>`;
        tr.innerHTML= tr.innerHTML +=`<td>${item.street}</td>`;
        tr.innerHTML= tr.innerHTML +=`<td>${item.house}</td>`;
        tBody.append(tr)
    })
}

async function requestServer(payload) {
    const response = await fetch('worker.php', {
        method: 'POST',
        body: payload
    })
    if (response.status >= 200 && response.status < 300) {
        return JSON.parse(await response.text())
    }
    throw new Error(response.statusText)
}

function setCities(data) {
    const el = document.querySelector('#city');
    el.innerHTML = '<option value="">Выберите город...</option>';
    data.map(item => {
        el.innerHTML += `<option value="${item.code}">${item.name} ${item.socr}</option>`
    })
}

function updateStatus(status) {
    const el = document.querySelector('#save');
    if (status === 'success') {
        el.innerHTML = 'Успешно сохранили';
    } else {
        el.innerHTML = 'Что-то пошло не так, сохранение не удалось';
    }
}

