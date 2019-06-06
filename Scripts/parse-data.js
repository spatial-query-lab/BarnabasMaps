async function main()
{
    let done = false;
    let index = 2;

    // While not done
    while(done === false)
    {
        let response = await parseCSV(index);
        
        index++;
        if(response === "Done!")
        {
            done = true;
        }
    }
}

async function parseCSV(index)
{
    let response = await $.ajax({
        method: "post",
        url: "../Data/CSVs/parse-csv.php",
        data:{index: index}
    });

    console.log(response);

    return response;
}