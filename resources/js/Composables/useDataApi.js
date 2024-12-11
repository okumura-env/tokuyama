import { ref, onMounted } from "vue"

export default function useDataApi(url, dataProcessor = (data) => data) {
    const data = ref([]); 
    const fetchData = async() => {
        try{
        const response = await axios.get(url);
        data.value = dataProcessor(response.data.data);
        }catch(error){
            console.error(`データの取得に失敗しました: ${url}`, error);
        }
    };

     onMounted(()=>{
        fetchData();
    });

    return {
        data,
        fetchData,
    }
}
