export default {
    data(){
        return {

        }
    },
    methods: {
        async callApi(method, url, dataObj) {
            try {
               return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                })
            } catch (e) {
                return e.response
            }
        },
        i (desc, title = 'Yaxshi!') {
            this.$Notice.info({
                desc: desc,
                title: title
            });
        },
        s (desc, title = "Zo'r!") {
            this.$Notice.success({
                desc: desc,
                title: title
            });
        },
        w (desc, title = 'Oops!') {
            this.$Notice.warning({
                desc: desc,
                title: title
            });
        },
        err (desc, title = 'Xatolik!') {
            this.$Notice.error({
                desc: desc,
                title: title
            });
        },
        swr (desc = "Nimadir nitto ketti! Qayta tekshirib ko'ring.", title = 'Oops') {
            this.$Notice.error({
                desc: desc,
                title: title
            });
        },
        formatPrice(value){
            return Number((value).toFixed(3))
        },
        myDateFormat(date, format){
            let myDate = new Date(date)
            let year = myDate.getFullYear()
            let month = myDate.getMonth() + 1
            let day = myDate.getDate()
            let hour = myDate.getHours()
            let minut = myDate.getMinutes()

            let formattedDate = day.toString().padStart(2, '0')+"."+month.toString().padStart(2, '0')+"."+year;
            if(format == "yyyy-mm-dd") {
                formattedDate = year+"-"+month.toString().padStart(2, '0')+"-"+day.toString().padStart(2, '0');
            } else if(format == "MM.DD.YYYY hh:mm") {
                formattedDate = day.toString().padStart(2, '0')+"."+month.toString().padStart(2, '0')+"."+year+" "+hour.toString().padStart(2, '0')+":"+minut.toString().padStart(2, '0');
            }

            return formattedDate;
        },
        roundPriceUp(number){
            var n = (number/100)
            n = Math.ceil(n)
            n = (n * 100)

            return n
        }
    }
}
