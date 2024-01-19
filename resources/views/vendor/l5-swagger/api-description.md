# API Documentation

Welcome to the API documentation of the CMORE.

Our API is designed to provide seamless integration between your application and our services. We offer a range of endpoints to help you access the data you need, from product information to user management. Our API is built with security in mind, so you can trust that your data is safe and secure.

We understand the importance of flexibility and customization, so our API is designed to be easily configurable to fit your unique needs. We offer extensive documentation and support to help you get started quickly and efficiently.

Our team is committed to providing the best possible service, so we are constantly improving our API and adding new features to better serve our customers. With our API, you can focus on building your application while we take care of the data integration.

Thank you for considering our API for your application. We are confident that you will find it to be a valuable asset to your business. If you have any questions or need assistance, our team is always here to help.

------

## Authentication

To use our API, you need to authenticate yourself.
To do this, you must request an API token to our team.
You will need to get a company secret key to use together with your token.

Once you have your token, you can use it to authenticate yourself to our API.

- **Header**: Authorization Bearer {token}
  - In the authentication header, you must pass the token you received from our team.
  - You need to send in this format: `Bearer {token}`.
  - `**required**`
- **Header**: X-Tenant {tenant secret key}
  - `**required**`
  -

---

### Special Questionnaire

> In our API documentation, we would like to highlight the existence of two special types of questionnaires: **Physical Risks** and **Taxonomy**. These questionnaires are designed to provide a more comprehensive data output, enabling users to gain deeper insights and make more informed decisions.

#### Physical Risks

>The **Physical Risks** questionnaire is specifically designed to assess and quantify the physical risks associated with specific activities or conditions. The output from this questionnaire provides detailed data on the nature and extent of these risks, allowing for more effective risk management and mitigation strategies.

>With regard to **Physical Risks**, a single questionnaire can contain multiple **Physical Risks**. These risks are typically specific to a certain geography and provide information about the local risk.

>In addition, the **Physical Risks** questionnaire also provides a history of changes. This allows users to track how **Physical Risks** have changed over time, which can be crucial for effective risk management.

#### Taxonomy

>The **Taxonomy** questionnaire, on the other hand, focuses on classifying and categorizing information according to a predefined system or set of principles. The output from this questionnaire provides a structured and organized view of the data, facilitating easier analysis and interpretation.

>**Taxonomy** can be applied in two distinct ways:

- Imported Activity: In this case, the activity is imported from an external source and therefore does not contain the user’s responses. This allows users to quickly import and classify large volumes of data.
- Non-Imported Activity: Here, the activity is not imported, which means it will include the user’s questions and answers. This allows for greater customization and can provide more detailed insights based on the user’s responses.

### License's

[MIT license](http://opensource.org/licenses/MIT)
[Apache License v2.0](http://www.apache.org/licenses/LICENSE-2.0)

### Contact

[CMORE](https://cmore-sustainability.com)

[Mail](mailto:contact@cmore.pt)

### Terms of Service

[CMORE Terms of Service](https://esg-maturity.com/storage/documentation/privacy-policy.pdf)
