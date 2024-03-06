namespace ASPDemo011232A02
{
    public class Program
    {
        public static void Main(string[] args)
        {
            // webapplication is special class provided by Microsoft: used to configure the HTTP
            // pipeline and routes
            var builder = WebApplication.CreateBuilder(args);

            builder.Services.AddControllers(); // without this your CORS services will fail upon attempted
            // Missing addCors() internal exception

            var app = builder.Build();  // create the webappp

            // Need to fix the CORS problem encountered with POST AJAX request
            // will allow web service to be called from any website

            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));

            app.UseDeveloperExceptionPage();  // Display error messages for developers. Remove it once everything is working fine

            //Simple Lamda statements are used for Demo purpose only
            app.MapGet("/", () => "Hello World!");

            app.MapGet("/special", () => "When you ask for special, I will provide it to you");

            // Check your URL to see how it maps to the following
            // Realize that if you want something complex, is it doable
            app.MapGet("/registerGet", (string getFirst, string getColor, string getAge) =>
             $"Client Data: {getFirst}'s favourite color is  {getColor} and he is {getAge} years old.");


            // One would think that passing for POST would be the same as the GET, one would be wrong
            //app.MapPost("/registerPOST", (string postFirst, string postColor, string postAge) =>
            //$"Client Data: {postFirst}'s favourite color is  {postColor} and he is {postAge} years old.");


            //AJAX calls for HTML data
            // UNCOMMENT THIS FOR HTML PART
            /*app.MapPost("/registerPOST", (Info submission) =>
            $"Client Data: {submission.postFirst}'s favourite color is  {submission.postColor} and he is {submission.postAge} years old.");
            */
            // UNCOMMENT THIS FOR JSON DATA
            app.MapPost("/registerPOST", (Info submission) => {
                // Process the inputs 
                //  Everthing else you want to do
                int timeToProcess = 45;
                double[] marks = { 2.5, 4.5 };

                return new
                {
                    FirstName = submission.postFirst,
                    Color = submission.postColor,
                    Age = submission.postAge,
                    TimeToProcess = timeToProcess,
                    Marks= marks
                };

            
            });

            app.Run();
        }
        record Info(string postFirst, string postColor, string postAge);
    }
}