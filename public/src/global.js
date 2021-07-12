var global = {
   checkAccessList: function(action){
       const actionID = loggedINAccessList.filter( entry => { return entry.action === action; });
       return actionID.length ? loggedINRoleAccessList.includes(actionID[0].id) : false;
   }
}
  
export default global