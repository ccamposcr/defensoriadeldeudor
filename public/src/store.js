import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
import repositories from './repositories';

const instructions = {
    state: {
        instructions: {
            show: false,
            textShow: 'Ver instrucciones',
            hideShow: 'Ocultar instrucciones'
        }
    },
    getters: {
        instructions: state => state.instructions
    },
    mutations: {
        setInstructions(state, visibility) {
            state.instructions.show = visibility;
        }
    }
}

export default new Vuex.Store({
    state: {
        showLoader: false,
        users: [],
        legalCases: [],
        staticData:{
            judicialStatusList: [],
            subjectList: [],
            administrativeStatusList: [],
            locationList: [],
            appointmentTypeList: [],
            roleList: [],
            accessList: []
        },
        locationStaticData: {'999': 'Archivo'},
        isClientInUse: 0,
        clientForm:{
            id:'',
            personalID:'',
            name: '',
            lastName1: '',
            lastName2: '',
            phone: '',
            email: '',
            address: '',
            roleID:'0',
            status: '1',
            phone2: '',
            phone3: '',
            email2: '',
            email3: '',
            job: '',
            inUse: '0'
        },
        isLegalCaseInUse: 0,
        legalCaseForm:{
            id: '',
            internalCode: '',
            subjectID: '',
            userID: '',
            judicialStatusID: '',
            note: '',
            legalCaseID: '',
            locationID: '',
            code: '',
            inUse: '0'
        },
        currentLegalCaseUserId: '',
        paymentDatesForm:{
            financialContractID: '',
            dates: []
        },
        legalCaseNotes: [],
        currentUserIdUpdatePassword: '',
        appointmentsDates: [],
        events: [],
        appointmentForm: {
            date: '',
            userID: '',
            internalUserID: '',
            madeByUserID: '',
            filterBy: '',
            clientList: [],
            usersList: [],
            alertColor: '#28a745',
            appointmentTypeID: ''
        },
        appointmentOriginalClientList: [],
        searchClientForm:{
            personalID: '',
            name: '',
            code: '',
            internalCode: '',
            searchBy: 'personalID'
        },
        updatePasswordForm:{
            password: '',
            confirmPassword: ''
        },
        administrationForm: [],
        editingUser: false,
        editingLegalCase: false,
        editingFinancialInfo: false,
        financialForm:{
            id: '',
            totalAmount: '',
            administrativeStatusID: '',
            userID: ''
        },
        currentFinancialInfoUserId: '',
        paymentDates: [],
        financialInfo: []
    },
    getters: {
        users: state => state.users,
        legalCases: state => userID => state.legalCases[userID],
        isClientInUse: state => state.isClientInUse,
        clientForm: state => state.clientForm,
        isLegalCaseInUse: state => state.isLegalCaseInUse,
        legalCaseForm: state => state.legalCaseForm,
        currentLegalCaseUserId: state => state.currentLegalCaseUserId,
        paymentDatesForm: state => state.paymentDatesForm,
        legalCaseNotes: state => legalCaseID => state.legalCaseNotes[legalCaseID],
        currentUserIdUpdatePassword: state => state.currentUserIdUpdatePassword,
        showLoader: state => state.showLoader,
        appointmentsDates: state => state.appointmentsDates,
        events: state => state.events,
        staticData: state => state.staticData,
        appointmentForm: state => state.appointmentForm,
        appointmentOriginalClientList: state => state.appointmentOriginalClientList,
        searchClientForm: state => state.searchClientForm,
        updatePasswordForm: state => state.updatePasswordForm,
        administrationForm: state => state.administrationForm,
        editingUser: state => state.editingUser,
        editingLegalCase: state => state.editingLegalCase,
        editingFinancialInfo: state => state.editingFinancialInfo,
        financialForm: state => state.financialForm,
        currentFinancialInfoUserId: state => state.currentFinancialInfoUserId,
        paymentDates: state => state.paymentDates,
        financialInfo: state => userID => state.financialInfo[userID]
    },
    mutations: {
        setJudicialStatusList(state, data){
            state.staticData.judicialStatusList = data;
        },
        setSubjectList(state, data){
            state.staticData.subjectList = data;
        },
        setAdministrativeStatusList(state, data){
            state.staticData.administrativeStatusList = data;
        },
        setLocationList(state, data){
            state.staticData.locationList = data;
        },
        setAppointmentTypeList(state, data){
            state.staticData.appointmentTypeList = data;
        },
        setRoleList(state, data){
            state.staticData.roleList = data;
        },
        setAccessList(state, data){
            state.staticData.accessList = data;
        },
        setClients(state, data){
            state.users = data;
        },
        setUsers(state, data){
            state.users = data;
        },
        setLegalCasesBy(state, {data, userID}){
            Vue.set(state.legalCases, userID, data);
        },
        setLegalCases(state, data){
            state.legalCases = data;
        },
        setIsClientInUse(state, data){
            state.isClientInUse = data;
        },
        setClientForm(state, data){
            state.clientForm = data;
        },
        setIsLegalCaseInUse(state, data){
            state.isLegalCaseInUse = data;
        },
        setLegalCaseForm(state, data){
            state.legalCaseForm = data;
        },
        setCurrentLegalCaseUserId(state, data){
            state.currentLegalCaseUserId = data;
        },
        setPaymentDatesForm(state, data){
            state.paymentDatesForm = data;
        },
        setLegalCaseNotesBy(state, {data, legalCaseID}){
            Vue.set(state.legalCaseNotes, legalCaseID, data);
        },
        setLegalCaseNotes(state, data){
            state.legalCaseNotes = data;
        },
        setCurrentUserIdUpdatePassword(state, data){
            state.currentUserIdUpdatePassword = data;
        },
        setShowLoader(state, data){
            state.showLoader = data;
        },
        setAppointmentsDates(state, data){
            state.appointmentsDates = data;
        },
        setEvents(state, data){
            state.events = data;
        },
        setAppointmentForm(state, data){
            state.appointmentForm = data;
        },
        setAppointmentFormBy(state, {data, by}){
            Vue.set(state.appointmentForm, by, data);
        },
        setAppointmentOriginalClientList(state, data){
            state.appointmentOriginalClientList = data;
        },
        setSearchClientForm(state, data){
            state.searchClientForm = data;
        },
        setUpdatePasswordForm(state, data){
            state.updatePasswordForm = data;
        },
        setAdministrationForm(state, data){
            state.administrationForm = data;
        },
        setEditingUser(state, data){
            state.editingUser = data;
        },
        setEditingLegalCase(state, data){
            state.editingLegalCase = data;
        },
        setEditingFinancialInfo(state, data){
            state.editingFinancialInfo = data;
        },
        setFinancialForm(state, data){
            state.financialForm = data;
        },
        setCurrentFinancialInfoUserId(state, data){
            state.currentFinancialInfoUserId = data;
        },
        setPaymentDates(state, data){
            state.paymentDates = data;
        },
        setFinancialInfoBy(state, {data, userID}){
            Vue.set(state.financialInfo, userID, data);
        },
        setFinancialInfo(state, data){
            state.financialInfo = data;
        },
    },
    actions: {
        async getJudicialStatusList(context) {
            try {
                const data = await repositories.getJudicialStatusList();
                const response = data.response;
                context.commit('setJudicialStatusList', response);   
            } catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getSubjectList(context) {
            try {
                const data = await repositories.getSubjectList();
                const response = data.response;
                context.commit('setSubjectList', response);   
            } catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAdministrativeStatusList(context){
            try {
                const data = await repositories.getAdministrativeStatusList();
                const response = data.response;
                context.commit('setAdministrativeStatusList', response);   
            } catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getLocationListData(context){
            try {
                const data = await repositories.getClientBy('roleID !=', '0');
                const response = data.response;

                response.forEach(item => {
                    item.location = item.name + ' ' + item.lastName1 + ' ' + item.lastName2;  
                });
                response.push({'location': context.state.locationStaticData['999'], 'id': '999'});
                context.commit('setLocationList', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getRoleList(context){
            try {
                const data = await repositories.getRoleList();
                const response = data.response;
                context.commit('setRoleList', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAccessList(context){
            try {
                const data = await repositories.getAccessList();
                const response = data.response;
                context.commit('setAccessList', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getRolePrivilegeAccess(context){
            try {
                const data = await repositories.getRolePrivilegeAccess();
                const response = data.response;
                context.commit('setAdministrationForm', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAllClients(context){
            try {
                const data = await repositories.getAllClients();
                const response = data.response;
                context.commit('setClients', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAllUsers(context){
            try {
                const data = await repositories.getAllUsers();
                const response = data.response;
                context.commit('setUsers', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getClientBy(context, {service, searchBy, value, callback}){
            try {
                const data = await repositories[service](searchBy, value);
                const response = data.response;
                context.commit('setClients', response);
                
                if(callback && response.length){
                    callback(response);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getLegalCasesBy(context, {searchBy, value, userID, callback}){
            try {
                const data = await repositories.getLegalCasesBy(searchBy, value);
                const response = data.response;

                response.forEach(item => {
                    item.location = item.locationID != '999' ? (item.name ? item.name : '') + ' ' + (item.lastName1 ? item.lastName1 : '') + ' ' + (item.lastName2 ? item.lastName2 : '') : context.state.locationStaticData['999'];
                });
                context.commit('setLegalCasesBy', {data:response, userID});

                if(callback && response.length){
                    callback(response);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getIsClientInUse(context, {id}){
            try {
                const data = await repositories.isClientInUse({'id': id});
                const response = data.response;
                let isInUse = 0;
                
                if( response.length ){
                    isInUse = response[0].inUse;
                }
                context.commit('setIsClientInUse', isInUse);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async fillClientForm(context, {id}){
            try {
                const data = await repositories.getClientBy('id', id);
                const response = data.response;
                if( response.length ){
                    context.commit('setClientForm', response[0]);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async updateClientIsInUse(context, {id, inUse}){
            try {
                await repositories.updateClientIsInUse({'id': id, 'inUse': inUse});
                context.commit('setIsClientInUse', inUse);
                
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getIsLegalCaseInUse(context, {id}){
            try {
                const data = await repositories.isLegalCaseInUse({'id': id});
                const response = data.response;
                let isInUse = 0;
                
                if( response.length ){
                    isInUse = response[0].inUse;
                }

                context.commit('setIsLegalCaseInUse', isInUse);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async fillLegalCaseForm(context, {id}){
            try {
                const data = await repositories.getLegalCasesBy('id', id);
                const response = data.response;

                if( response.length ){
                    response[0].id = id;
                    context.commit('setLegalCaseForm', response[0]);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        /*async fillPaymentDatesOnForm(context, {id}){
            try {
                const data = await repositories.getPaymentDatesBy('legalCaseID', id);
                const response = data.response;
                if( response.length ){
                  const tmpData = {
                    legalCaseID : id,
                    dates: response
                  };
                  context.commit('setPaymentDatesForm', tmpData);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },*/
        async updateLegalCaseIsInUse(context, {id, inUse}){
            try {
                await repositories.updateLegalCaseIsInUse({'id': id, 'inUse': inUse});
                context.commit('setIsLegalCaseInUse', inUse);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getLegalCaseNotesBy(context, {searchBy, legalCaseID}){
            try {
                const data = await repositories.getLegalCaseNotesBy(searchBy, legalCaseID);
                const response = data.response;
                context.commit('setLegalCaseNotesBy', {data:response, legalCaseID});
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        /*async getPaymentDatesBy(context, {searchBy, legalCaseID}){
            try {
                const data = await repositories.getPaymentDatesBy(searchBy, legalCaseID);
                const response = data.response;
                context.commit('setPaymentDatesBy', {data:response, legalCaseID});
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },*/
        async getAppointmentTypeList(context){
            try {
                const data = await repositories.getAppointmentTypeList();
                const response = data.response;
                context.commit('setAppointmentTypeList', response);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getPaymentDatesByDateRange(context, {startDate, endDate}){
            try {
                const data = await repositories.getPaymentDatesByDateRange(startDate, endDate);
                const response = data.response;
                context.commit('setPaymentDates', response);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAppointmentsByDateRange(context, {searchBy, startDate, endDate}){
            try {
                const data = await repositories.getAppointmentsByDateRange(searchBy, startDate, endDate);
                const response = data.response;
                context.commit('setAppointmentsDates', response);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async fillAppointmentForm(context){
            
            const buildUserClientName = function(data){
                data.forEach(item => {
                item.client = item.personalID + ' -> ' + item.name + ' ' + item.lastName1 + ' ' + item.lastName2;
                item.userID = item.id;
                });
                return data;
            }

            try {

                const dataClients = await repositories.getAllClients();
                const responseClients = dataClients.response;

                const clientsFormatted = buildUserClientName(responseClients);
                context.commit('setAppointmentFormBy', {data:clientsFormatted, by:'clientList'});
                context.commit('setAppointmentOriginalClientList', clientsFormatted);

                const dataUsers = await repositories.getAllUsers();
                const responseUsers = dataUsers.response;

                const usersFormatted = buildUserClientName(responseUsers);
                context.commit('setAppointmentFormBy', {data:usersFormatted, by:'usersList'});

            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getFinancialInfoBy(context, {searchBy, value, userID, callback}){
            try {
                const data = await repositories.getFinancialInfoBy(searchBy, value);
                const response = data.response;

                context.commit('setFinancialInfoBy', {data:response, userID});

                if(callback && response.length){
                    callback(response);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        }
    },
    modules: {
        instructions: instructions
    }
})