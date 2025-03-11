## Technical Questions
- How long did you spend on the coding test? What additional features would you consider implementing if you had more time?
**Ans:** I spent over 3 hours on the implementation. With more time, I would refine the properties query for simplicity and improve the /properties/:province route on the frontend and add error handling.
__
- Describe a security best practice you would implement in this application to protect the API.
**Ans:** I would use Laravel Sanctum to secure the API.
__
- Explain how you would approach optimizing the performance of the API for handling large amounts of property data.
**Ans:** I would implement caching to improve performance.
__
- How would you track down a performance issue in production? Have you ever had to do this? If so, please describe the experience.
**Ans:** Yes, I have. I once resolved a memory issue when exporting a full year's attendance records to Excel. The server crashed due to the large data load, so I implemented chunked writing and adjusted the PHP memory limit to prevent downtime eventhough a single user export is failed.