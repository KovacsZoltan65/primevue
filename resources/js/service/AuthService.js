export const AuthService = {

    logout(){
        //console.log('AuthService.logout()');
        axios.post('/logout', {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        })
            .then(response => {
                console.log('response', response);
                route('login');
            })
            .catch(error => {
                console.log('error', error);
            });
    }

};