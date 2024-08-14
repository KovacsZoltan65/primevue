export const CompanyService = {
    
    getData(){
        return [
            { id: 1, name:  'Company 01' },
            { id: 2, name:  'Company 02' },
            { id: 3, name:  'Company 03' },
            { id: 4, name:  'Company 04' },
            { id: 5, name:  'Company 05' },
            { id: 6, name:  'Company 06' },
            { id: 7, name:  'Company 07' },
            { id: 8, name:  'Company 08' },
            { id: 9, name:  'Company 09' },
            { id: 10, name: 'Company 10' },
        ];
    },
        
    getCompanies(){        
        return Promise.resolve(this.getData());
    }
};