var repositories = {
    getClientBy: async function(searchBy, value){
        const url = 'clientes/getClientBy';
        
        const params = {
            'searchBy':searchBy,
            'value' :value
        };
        params[csrf_name] = csrf_hash;
        const response = await fetch(url, {
            credentials: 'include',
            method: 'POST',
            body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getRoleList: async function(){
        const url = 'clientes/getRoleList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getJudicialStatusList: async function(){
        const url = 'clientes/getJudicialStatusList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getSubjectList: async function(){
        const url = 'clientes/getSubjectList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getAllUsers: async function(){
        const url = 'clientes/getAllClients';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getAdministrativeStatusList: async function(){
        const url = 'clientes/getAdministrativeStatusList';
        const response = await fetch(url);
        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getLegalCasesBy: async function(searchBy, value){
        const url = 'clientes/getLegalCasesBy';

        const params = {
          'searchBy':searchBy,
          'value': value
        };
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getLegalCaseNotesBy: async function(searchBy, value){
        const url = 'clientes/getLegalCaseNotesBy';

        const params = {
          'searchBy':searchBy,
          'value': value
        };
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    getLegalCasesByDateRange: async function(searchBy, start, end){
        const url = 'inicio/getLegalCasesByDateRange';

        const params = {
          'searchBy':searchBy,
          'start': start,
          'end': end
        };
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;
        return data;
    },
    addLegalCaseNote: async function(params){
        const url = 'clientes/addLegalCaseNote';

        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
            credentials: 'include',
            method: 'POST',
            body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        return data;
    },
    addNewClient: async function(params){
        const url = 'clientes/addClient';
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
            credentials: 'include',
            method: 'POST',
            body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        return data;
    },
    editClient: async function(params){
        const url = 'clientes/editClient';
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
            credentials: 'include',
            method: 'POST',
            body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        return data;
    },
    addNewLegalCase: async function(userID, params){
        const url = 'clientes/addLegalCase';
        params[csrf_name] = csrf_hash;
        params['userID'] = userID;

        const response = await fetch(url, {
            credentials: 'include',
            method: 'POST',
            body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        return data;
    },
    editLegalCase: async function(params){
        const url = 'clientes/editLegalCase';
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
          credentials: 'include',
          method: 'POST',
          body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        return data;
    },
    addNewAppointment: async function(params){
        console.log(params);
        const url = 'citas/addAppointment';
        params[csrf_name] = csrf_hash;

        const response = await fetch(url, {
            credentials: 'include',
            method: 'POST',
            body: new URLSearchParams(params)
        });

        const data = await response.json();
        csrf_name = data.csrf_name;
        csrf_hash = data.csrf_hash;

        return data;
    }
}
  
export default repositories