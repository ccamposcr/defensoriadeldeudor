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
        const url = 'generic/getRoleList';
        const response = await fetch(url);
        const data = await response.json();

        return data;
    },
    getJudicialStatusList: async function(){
        const url = 'generic/getJudicialStatusList';
        const response = await fetch(url);
        const data = await response.json();

        return data;
    },
    getSubjectList: async function(){
        const url = 'generic/getSubjectList';
        const response = await fetch(url);
        const data = await response.json();

        return data;
    },
    getAllUsers: async function(){
        const url = 'clientes/getAllClients';
        const response = await fetch(url);
        const data = await response.json();

        return data;
    },
    getAdministrativeStatusList: async function(){
        const url = 'generic/getAdministrativeStatusList';
        const response = await fetch(url);
        const data = await response.json();

        return data;
    },
    getLegalCasesBy: async function(searchBy, value){
        const url = 'casosLegales/getLegalCasesBy';

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
        const url = 'casosLegales/getLegalCaseNotesBy';

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
        const url = 'casosLegales/getLegalCasesByDateRange';

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
        const url = 'casosLegales/addLegalCaseNote';

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
        const url = 'casosLegales/addLegalCase';
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
        const url = 'casosLegales/editLegalCase';
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
    },
    getAppointmentsByDateRange: async function(searchBy, start, end){
        const url = 'citas/getAppointmentsByDateRange';

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
    cancelAppointment: async function(params){
        const url = 'citas/cancelAppointment';
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
    getAccessList:  async function(){
        const url = 'generic/getAccessList';
        const response = await fetch(url);
        const data = await response.json();

        return data;
    },
    setRolePrivilegeAccess: async function(params){
        const url = 'generic/setRolePrivilegeAccess';
        params[csrf_name] = csrf_hash;
        params.data = JSON.stringify(params.data);

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
    getRolePrivilegeAccess:  async function(){
        const url = 'generic/getRolePrivilegeAccess';
        const response = await fetch(url);
        const data = await response.json();

        return data;
    }
}
  
export default repositories