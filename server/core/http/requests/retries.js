async the_thing_where_retries_needed() {
            let attempts = 0;
            while (attempts < 100) {
                try {
                    // here try to fetch data
                    if (data) return data;

                    console.log(`Status: Waiting for the data... (${attempts + 1}/100)`);
                    await new Promise(res => setTimeout(res, 5000));
                } catch (err) {
                    console.error('Status: Error fetching data details:', err.body?.message || 'Unknown error');
                    return null;
                }
                attempts++;
            }
            return null;
        }


//Usage
const the_data = await the_thing_where_retries_needed();
if (ipAddress) {
    console.log(`Status: Data fetched successfully!`);
} 
// make sure everything after should have await, so the wait for the_thing_where_retries_needed() before proceeding, to prevent race conditions